<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 10/11/2020
 * Time: 16:14
 */

namespace open20\amos\mobile\bridge\utility;


use open20\amos\admin\models\UserProfile;
use open20\amos\chat\models\Message;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\ChatMessages;
use open20\amos\mobile\bridge\modules\v1\models\User;
use open20\amos\news\models\News;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Console;
use yii\rest\Controller;
use paragraph1\phpFCM\Recipient\Device;

class NotificationPushUtility
{

    /**
     *
     * @param type $user_id
     * @param type $title
     * @param type $body
     * @param type $content_type
     * @param type $content_id
     * @return boolean
     */
    public static function sendNotification($user_id, $title, $body, $content_type, $content_id)
    {
        $ret = false;
        $user = User::findOne(['id' => $user_id]);
        Console::stdout("Notifica a $user_id" . PHP_EOL);


        \Yii::error("Notifica a {$user_id}");

        if ($user && $user->id) {
            /**
             * @var ActiveQuery $q
             */
            $q = AccessTokens::find();
            $q->groupBy(['fcm_token', 'device_os']);
            $q->andWhere(['user_id' => $user_id]);

            //All tokens
            $tokens = $q->all();

            //Se non ci sono tokens a cui mandare salto la procedura
            if (!$tokens || !count($tokens)) {
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
                    $expopush = new \open2\expo\ExpoPush();
                    if ($token->fcm_token != 'webcms') {
                        $result = $expopush->notify($token->fcm_token, $notification);
                    }
                    if ($result[0]['status'] == 'error') {
                        if ($result[0]['details']['error'] == 'DeviceNotRegistered') {
                            $token->delete();
                        }
                    }

//                    Console::stdout('--- Device ' . json_encode($result) . PHP_EOL);

                    \Yii::error("Risultato notifica " . json_encode($result));
                }
            }
            $ret = true;
        }
        return $ret;
    }

}