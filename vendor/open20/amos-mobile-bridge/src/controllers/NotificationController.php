<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\chat\models\Message;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\ChatMessages;
use open20\amos\mobile\bridge\modules\v1\models\User;
use open20\amos\news\models\News;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\rest\Controller;
use paragraph1\phpFCM\Recipient\Device;

class NotificationController extends Controller
{
    /**
     * @param $event Event
     */
    public function afterActiveRecordCreate($event)
    {
        try {
            $senderClass = get_class($event->sender);

            switch ($senderClass) {
                case Message::className():
                    {
                        $this->sendNotificationByType('chat', $event->sender);
                    }
                    break;
                case ChatMessages::className():
                    {
                        $this->sendNotificationByType('chat', $event->sender);
                    }
                    break;
                case Comment::className():
                    {
                        $this->sendNotificationByType('comment', $event->sender);
                    }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }

    }

    /**
     * @param $record Notification
     */
    public function sendStandardNotification($record)
    {
        switch ($record->class_name) {
            case News::className():
                {
                    //
                }
                break;
            case Community::className():
                {
                    //
                }
        }
    }

    /**
     * @param $type string
     * @param $record ActiveRecord
     */
    public function sendNotificationByType($type, $record)
    {
        switch ($type) {
            case 'chat':
                {
                    $senderUser = UserProfile::findOne(['user_id' => $record->created_by]);

                    $this->sendNotification(
                        $record->receiver_id,
                        \Yii::t('amosmobilebridge', 'Message from') . " {$senderUser->nome} {$senderUser->cognome}",
                        strip_tags($record->text),
                        'chat',
                        $record->sender_id
                    );
                }
                break;
            case 'comment':
                {
                    $contentClass = $record->context;
                    $identifier = $this->getContentIdentiffierByClassname($contentClass);

                    if ($identifier) {
                        $content = $this->getContentByComment($record);

                        $this->sendNotification(
                            $content->created_by,
                            \Yii::t('amosmobilebridge', 'New Comment In Your Content'),
                            \Yii::t('amosmobilebridge', 'New comment has been published in yout content'),
                            $identifier,
                            $content->id
                        );
                    }
                }
                break;
        }
    }

//    public function sendNotification($user_id, $title, $body, $content_type, $content_id)
//    {
//        $user = User::findOne(['id' => $user_id]);
//
//        if ($user && $user->id) {
//            $note = $this->module->fcm->createNotification($title, $body);
//
//            $note->setIcon('notification_icon_resource_name')
//                ->setColor('#ffffff')
//                ->setBadge(1);
//
//            $message = $this->module->fcm->createMessage();
//
//
//            /**
//             * @var ActiveQuery $q
//             */
//            $q = AccessTokens::find();
//            $q->groupBy(['fcm_token', 'device_os']);
//            $q->andWhere(['user_id' => $user_id]);
//
//            //All tokens
//            $tokens = $q->all();
//
//            //Se non ci sono tokens a cui mandare salto la procedura
//            if(!$tokens || !count($tokens)) {
//                return false;
//            }
//
//            foreach ($tokens as $token) {
//                if (!empty($token->fcm_token)) {
//                    $message->addRecipient(new Device($token->fcm_token));
//                }
//            }
//
//            $message->setNotification($note);
//            $message->setData([
//                'targetId' => $content_id,
//                'targetScreen' => $content_type
//            ]);
//
//            $response = $this->module->fcm->send($message);
//        }
//    }
    
    /**
     * 
     * @param type $user_id
     * @param type $title
     * @param type $body
     * @param type $content_type
     * @param type $content_id
     * @return boolean
     */
    public function sendNotification($user_id, $title, $body, $content_type, $content_id)
    {
        $ret = false;
        $user = User::findOne(['id' => $user_id]);

        \Yii::error("Notifica a {$user_id}");

        if ($user && $user->id) 
        {
            /**
             * @var ActiveQuery $q
             */
            $q = AccessTokens::find();
            $q->groupBy(['fcm_token', 'device_os']);
            $q->andWhere(['user_id' => $user_id]);

            //All tokens
            $tokens = $q->all();

            //Se non ci sono tokens a cui mandare salto la procedura
            if(!$tokens || !count($tokens)) {
                return false;
            }
            $note = new \open2\expo\Message($title, $body);
            $note->setIcon('notification_icon_resource_name')
               ->setColor('#ffffff')
               ->setBadge(1)
                //->setPriority('max');
                ->setChannelId('pushChannel');
            $data = [
                'targetId' => $content_id,
                'targetScreen' => $content_type
            ];
            \Yii::error("Data notifica " . json_encode($data));
            $note->setData($data);
            $notification = $note->buildMessage();
            foreach ($tokens as $token) {
                if (!empty($token->fcm_token)) {
                    $result = $this->module->expo->notify($token->fcm_token, $notification);
                    \Yii::error("Risultato notifica " . json_encode($result));
                }
            }
            $ret = true;
        }
        return $ret;
    }

    /**
     * @param $comment Comment
     * @return ActiveRecord
     */
    public function getContentByComment($comment)
    {
        $context = $comment->context;

        /**
         * @var ActiveRecord $context
         */
        $content = $context::findOne($comment->context_id);

        return $content;
    }

    /**
     * @param $classname
     * @return bool|mixed
     */
    public function getContentIdentiffierByClassname($classname)
    {
        $contentsArray = [
            News::className() => 'news',
            DiscussioniTopic::className() => 'discussioni',
        ];

        return isset($contentsArray[$classname]) ? $contentsArray[$classname] : false;
    }
}