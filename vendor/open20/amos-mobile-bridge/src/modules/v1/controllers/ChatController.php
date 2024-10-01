<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\controllers;


use open20\amos\admin\models\UserProfile;
use open20\amos\chat\models\Conversation;
use open20\amos\mobile\bridge\modules\v1\models\ChatMessages;
use open20\amos\mobile\bridge\modules\v1\models\User;
use yii\db\ActiveQuery;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\Response;

class ChatController extends DefaultController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours, [
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'chat-list' => ['post'],
                    'chat-detail' => ['get'],
                    'send-message' => ['get'],
                    'new-chat' => ['get'],
                    'user-list' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * @inheritdoc
     *
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'chat-list' => ['post'],
                    'chat-detail' => ['post'],
                    'send-message' => ['post'],
                    'new-chat' => ['post'],
                    'user-list' => ['post'],
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
            ],
        ];
    }*/

    /**
     * 
     * @return type
     */
    public function actionChatList()
    {
        $module = $this->module;
        $userID = \Yii::$app->user->id;

        /**
         * @var $q ActiveQuery
         */
        $q = ChatMessages::find();
        //$q->alias('conversation');
        $q->select([
            'sender.id',
            'sender.nome',
            'sender.cognome',
            'sender_id',
           'receiver_id',
           //'id message_id',
            //'conversation.text',
            '('. ChatMessages::tablename() .'.created_at) datetime'
        ]);
        $q->innerJoin("user_profile as sender", "sender.user_id = IF(`".ChatMessages::tablename()."`.`receiver_id`={$userID},".ChatMessages::tablename().".sender_id,".ChatMessages::tablename().".receiver_id)");
        $q->andWhere([ChatMessages::tablename().'.receiver_id' => $userID]);
        $q->orWhere([ChatMessages::tablename().'.sender_id' => $userID]);
        //$q->groupBy(['sender.id']);
        $q->orderBy(['datetime' => SORT_DESC]);
        $q->asArray();
//pr($q->createCommand()->rawSql);die;
        $chats = $q->all();
        $owners = [];

        foreach ($chats as $k=>$chat) {
            if(!isset($owners[$chat['id']])) {
                $owners[$chat['id']] = UserProfile::findOne(['id' => $chat['id']]);
            }

            //Set contact id to fetch last message
            $contact_id = $chat['receiver_id'];

            //If the sender is not me is the contact so set it up
            if($chat['sender_id'] != $userID) {
                $contact_id = $chat['sender_id'];
            }

            /**
             * @var $lmq ActiveQuery
             */
            $lmq = $this->getMessagesQuery($contact_id);
            $lmq->orderBy(['message.created_at' => SORT_DESC]);

            $lastMessage = $lmq->asArray()->one();

            //Creator profile
            $owner =  $owners[$chat['id']];

            $chat['avatarUrl'] = $owner->avatarWebUrl;
            $chat['text'] = html_entity_decode(strip_tags($lastMessage['text']));

            $chats[$k] = $chat;
        }

        return $chats;
    }

    /**
     * 
     * @param type $contact_id
     * @return type
     */
    public function actionChatDetail($contact_id)
    {
        $userID = \Yii::$app->user->id;

        /**
         * @var $q ActiveQuery
         */
        $q = $this->getMessagesQuery($contact_id);
        $q->asArray();

        $messages = $q->all();
        $owners = [];

        foreach ($messages as $k=>$message) {
            if(!isset($owners[$message['created_by']])) {
                $owners[$message['created_by']] = UserProfile::findOne(['id' => $message['created_by']]);
            }

            $messageObject = ChatMessages::findOne(['id' => $message['id']]);
            $messageObject->is_new = false;
            $messageObject->save();

            //Creator profile
            $owner =  $owners[$message['created_by']];

            $message['avatarUrl'] = $owner->avatarWebUrl;
            $message['text'] = html_entity_decode(strip_tags($message['text']));

            $messages[$k] = $message;
        }

        return $messages;
    }

    /**
     * Return the active query for chat messages
     * @param $contact_id
     * @return mixed
     */
    public function getMessagesQuery($contact_id) {
        $userID = \Yii::$app->user->id;

        $q = ChatMessages::find();
        $q->alias('message');
        $q->select([
            'message.id',
            'message.text',
            'message.created_at',
            'message.created_by',
            'sender_id',
            'receiver_id',
            'sender.nome as sender_name',
            'receiver.nome as receiver_name'
        ]);
        $q->innerJoin('user_profile as sender', 'sender.user_id = message.sender_id');
        $q->innerJoin('user_profile as receiver', 'receiver.user_id = message.receiver_id');
        $q->where(['receiver_id' => $userID, 'sender_id' => $contact_id]);
        $q->orWhere(['sender_id' => $userID, 'receiver_id' => $contact_id]);
        $q->orderBy(['message.created_at' => SORT_DESC]);

        return $q;
    }

    public function actionSendMessage($contact_id, $text)
    {
        $userID = \Yii::$app->user->id;
        $message = new ChatMessages();
        $message->sender_id = $userID;
        $message->receiver_id = $contact_id;
        $message->text = $text;
        $message->save(false);

        return true;
    }

    public function actionNewChat()
    {
        return true;
    }

    public function actionUserList()
    {
        $userID = \Yii::$app->user->id;

        /**
         * @var $q ActiveQuery
         */
        $q = User::find();
        $q->alias('user');
        $q->select(["IF(`contact`.`contact_id`={$userID},contact.user_id,contact.contact_id) as id",'profile.nome', 'profile.cognome']);
        $q->innerJoin('user_contact as contact', "(contact.user_id = user.id OR contact.contact_id = user.id) AND contact.deleted_at IS NULL");
        $q->innerJoin('user_profile as profile', "profile.user_id = IF(`contact`.`contact_id`={$userID},contact.user_id,contact.contact_id)");
        //$q->where(['!=','user.id', $userID]);
        $q->where('user.deleted_at IS NULL');

        if(\Yii::$app->params['skipContacts'] !== true) {
            $q->andWhere(['contact.status' => 'ACCEPTED']);
            $q->andWhere('contact.contact_id=:uid OR contact.user_id=:uid', [':uid' => $userID]);
        }

        $q->orderBy('profile.nome ASC, profile.cognome ASC');
        //$users->limit(20);
        $q->asArray(true);

        $users = $q->all();
        $owners = [];

        foreach ($users as $k=>$user) {
            if(!isset($owners[$user['id']])) {
                $owners[$user['id']] = UserProfile::findOne(['user_id' => $user['id']]);
            }

            //Creator profile
            $owner =  $owners[$user['id']];

            $user['avatarUrl'] = $owner->avatarWebUrl;

            $users[$k] = $user;
        }

        return $users;
    }

}