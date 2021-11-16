<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\models
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models;

use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use yii\base\BaseObject;
use yii\db\Query;

/**
 * Class NotificationChannels
 * @package open20\amos\notificationmanager\models
 */
class NotificationChannels extends BaseObject
{
    const MANAGE_UP = 'up';
    const MANAGE_DOWN = 'down';
    
    //const
    const CHANNEL_MAIL = 0x0001;            // dec. 1
    const CHANNEL_IMMEDIATE_MAIL = 0x0002;  // NOT USED
    const CHANNEL_UI = 0x0004;              // dec. 4
    const CHANNEL_NEWSLETTER = 0x0010;      // dec. 16
    const CHANNEL_SMS = 0x00F0;             // dec. 240
    const CHANNEL_READ = 0x0F00;            // dec. 3840
    const CHANNEL_READ_DETAIL = 0x0F01;     // dec. 3841
    const CHANNEL_FAVOURITES = 0xE290;      // dec. 58000
    const CHANNEL_ALL = 0xFFFF;             // dec. 65535

    /**
     * @var AmosNotify $notifyModule
     */
    protected $notifyModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->notifyModule = AmosNotify::instance();
    }
    
    /**
     * @param string $modelClassName
     * @param string $type
     */
    public function manageNewChannelNotifications($modelClassName, $channel, $type)
    {
        $retval = [
            'success' => false,
            'errors' => ''
        ];
        switch ($type) {
            case self::MANAGE_UP:
                $retval = $this->addChannelNotifications($modelClassName, $channel);
                break;
            case self::MANAGE_DOWN:
                $retval = $this->removeChannelNotifications($channel);
                break;
        }
        return $retval;
    }
    
    /**
     * @param string $modelClassName
     * @return array
     */
    private function addChannelNotifications($modelClassName, $channel)
    {
        $moduleNotify = \Yii::$app->getModule('notify');
        $allOk = true;
        $retval = [
            'success' => $allOk,
            'errors' => []
        ];
        $modelsData = $this->findAllModuleModels($modelClassName);

        /** @var array $modelData */
        foreach ($modelsData as $modelData) {
//            if($moduleNotify && $moduleNotify->confirmEmailNotification) {
//                //used for the modal to choose if you want to send the email notification
//                $ok = $this->saveNotificationSendEmail($modelClassName, $channel, $modelData);
//                if (!$ok) {
//                    $retval['errors'][] = AmosNotify::t('amosnotify', 'Error during add of notification') .
//                        '. content_id = ' . $modelData['id'] . '; class_name = ' . $modelClassName .
//                        '; channels = ' . $channel . ';';
//                    $allOk = false;
//                }
//            }

            /** @var Notification $notificationModel */
            $notificationModel = $this->notifyModule->createModel('Notification');
            $notification = $notificationModel::find()->andWhere([
                'channels' => $channel,
                'content_id' => $modelData['id'],
                'class_name' => $modelClassName
            ])->one();
            if (is_null($notification)) {
                /** @var Notification $notificationModel */
                $notification = $this->notifyModule->createModel('Notification');
                $notification->channels = $channel;
                $notification->content_id = $modelData['id'];
                $notification->class_name = $modelClassName;
            }
            $notification->user_id = 1;
            $ok = $notification->save(false);
            if (!$ok) {
                $retval['errors'][] = AmosNotify::t('amosnotify', 'Error during add of notification') .
                    '. content_id = ' . $modelData['id'] . '; class_name = ' . $modelClassName .
                    '; channels = ' . $channel . ';';
                $allOk = false;
            }
        }
        $retval['success'] = $allOk;
        return $retval;
    }
    
    /**
     * @param string $modelClassName
     * @return array
     */
    private function findAllModuleModels($modelClassName)
    {
        $results = [];
        if (is_string($modelClassName) && strlen($modelClassName)) {
            /** @var Record $modelClassName */
            $query = new Query();
            $query->select(['id']);
            $query->from($modelClassName::tableName());
            $results = $query->all();
        }
        return $results;
    }
    
    /**
     * @param int $contentId
     * @param string $modelClassName
     * @return array
     */
    private function removeChannelNotifications($channel)
    {
        $allOk = true;
        $retval = [
            'success' => $allOk,
            'errors' => []
        ];
        /** @var Notification $notificationModel */
        $notificationModel = $this->notifyModule->createModel('Notification');
        $notifications = $notificationModel::find()->andWhere(['channels' => $channel])->all();
        foreach ($notifications as $notification) {
            /** @var NotificationsRead $notificationsReadModel */
            $notificationsReadModel = $this->notifyModule->createModel('NotificationsRead');
            /** @var Notification $notification */
            $notificationsRead = $notificationsReadModel::find()->andWhere(['notification_id' => $notification->id])->all();
            $nrOk = true;
            foreach ($notificationsRead as $notificationRead) {
                /** @var NotificationsRead $notificationRead */
                $nrErrorMsg = AmosNotify::t('amosnotify', 'Error while removing of notification read') . '. ID = ' . $notificationRead->id;
                $notification->delete();
                if ($notification->getErrors()) {
                    $retval['errors'][] = $nrErrorMsg;
                    $allOk = false;
                    $nrOk = false;
                }
            }
            if (!$nrOk) {
                break;
            }
            $errorMsg = AmosNotify::t('amosnotify', 'Error while removing of notification') . '. ID = ' . $notification->id;
            $notification->delete();
            if ($notification->getErrors()) {
                $retval['errors'][] = $errorMsg;
                $allOk = false;
            }
        }
        $retval['success'] = $allOk;
        return $retval;
    }

    /**
     * @param $modelClassName
     * @param $channel
     * @param $modelData
     * @return bool
     */
    private function saveNotificationSendEmail($modelClassName, $channel, $modelData){
        if($channel == NotificationChannels::CHANNEL_MAIL) {
            /** @var NotificationSendEmail $notificationSendEmailModel */
            $notificationSendEmailModel = $this->notifyModule->createModel('NotificationSendEmail');
            $notificationSendEmail = $notificationSendEmailModel::find()->andWhere([
                'content_id' => $modelData['id'],
                'class_name' => $modelClassName
            ])->one();
            if (is_null($notificationSendEmail)) {
                /** @var NotificationSendEmail $notificationSendEmailModel */
                $notificationSendEmail = $this->notifyModule->createModel('NotificationSendEmail');
                $notificationSendEmail->content_id = $modelData['id'];
                $notificationSendEmail->class_name = $modelClassName;
            }

            $ok = $notificationSendEmail->save(false);
            return $ok;
        }
        return true;
    }
}
