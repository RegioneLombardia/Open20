<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use yii\db\Migration;

/**
 * Class m170619_152314_add_discussioni_favourite_channel_notifications
 */
class m170619_152314_add_discussioni_favourite_channel_notifications extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $notifyModule = Yii::$app->getModule('notify');
        if (is_null($notifyModule)) {
            MigrationCommon::printConsoleMessage(AmosDiscussioni::t('amosdiscussioni', 'Notify module not installed. Nothing to do.'));
            return true;
        }
        $retval = \open20\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            DiscussioniTopic::className(),
            \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \open20\amos\notificationmanager\models\NotificationChannels::MANAGE_UP);
        
        if (is_array($retval)) {
            if (!$retval['success']) {
                foreach ($retval['errors'] as $error) {
                    MigrationCommon::printConsoleMessage($error);
                }
            }
        }
        
        return $retval['success'];
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $notifyModule = Yii::$app->getModule('notify');
        if (is_null($notifyModule)) {
            MigrationCommon::printConsoleMessage(AmosDiscussioni::t('amosdiscussioni', 'Notify module not installed. Nothing to do.'));
            return true;
        }
        $retval = \open20\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            DiscussioniTopic::className(),
            \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \open20\amos\notificationmanager\models\NotificationChannels::MANAGE_DOWN);
        if (!$retval['success']) {
            foreach ($retval['errors'] as $error) {
                MigrationCommon::printConsoleMessage($error);
            }
        }
        return $retval['success'];
    }
}
