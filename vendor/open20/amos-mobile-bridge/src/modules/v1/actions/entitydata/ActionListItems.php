<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata;

use open20\amos\admin\models\UserProfile;
use open20\amos\community\models\Community;
use open20\amos\core\record\Record;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\DiscussioniParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\ItemsParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\NewsParser;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\User;
use open20\amos\news\models\News;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\notificationmanager\models\NotificationsRead;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii\rest\Action;

class ActionListItems extends Action
{

    public function run()
    {
        /**
         * @var $amosNotify AmosNotify
         */
        $amosNotify = AmosNotify::getInstance();

        //Request params
        $bodyParams = \Yii::$app->getRequest()->getBodyParams();

        //Refference namespace
        $namespace = $bodyParams['namespace'];

        //Default definition items array
        $itemsArray = [];

        switch ($namespace) {
            case News::className():
                {
                    $itemsArray = NewsParser::getItems($namespace, $bodyParams);
                }
                break;
            case DiscussioniTopic::className():
                {
                    $itemsArray = DiscussioniParser::getItems($namespace, $bodyParams);
                }
                break;
            default:
            {
                $itemsArray = ItemsParser::getItems($namespace, $bodyParams);
            }
        }

        if ($amosNotify && $amosNotify->id) {
            foreach ($itemsArray as $item) {
                $notification = Notification::findOne(
                    [
                        'content_id' => $item['id'],
                        'class_name' => $namespace,
                        'channels' => NotificationChannels::CHANNEL_READ
                    ]
                );

                if($notification && $notification->id) {
                    $notificationRead = NotificationsRead::findOne(
                        [
                            'user_id' => \Yii::$app->user->id,
                            'notification_id' => $notification->id
                        ]
                    );

                    $notificationRead ?: $notificationRead = new NotificationsRead();

                    $notificationRead->notification_id = $notification->id;
                    $notificationRead->user_id = \Yii::$app->user->id;
                    $notificationRead->save(false);
                }
            }
        }

        return $itemsArray;
    }
}