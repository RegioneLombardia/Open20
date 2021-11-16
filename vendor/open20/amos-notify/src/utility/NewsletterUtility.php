<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\utility
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\utility;

use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\exceptions\NewsletterException;
use open20\amos\notificationmanager\models\Newsletter;
use open20\amos\notificationmanager\models\NewsletterContentsConf;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\models\NotificationChannels;
use yii\base\BaseObject;
use yii\db\ActiveQuery;

/**
 * Class NewsletterUtility
 * @package open20\amos\notificationmanager\utility
 */
class NewsletterUtility extends BaseObject
{
    /**
     * This method add the notification row ready to be used by notifier controller.
     * @param AmosNotify $notifyModule
     * @param Newsletter $newsletter
     * @return bool
     */
    public static function addNewsletterNotification($notifyModule, $newsletter)
    {
        /** @var Notification $notification */
        $notification = $notifyModule->createModel('Notification');
        $notification->user_id = \Yii::$app->user->id;
        $notification->channels = NotificationChannels::CHANNEL_NEWSLETTER;
        $notification->content_id = $newsletter->id;
        $notification->class_name = $newsletter::className();
        $ok = $notification->save(false);
        return $ok;
    }
    
    /**
     * This method remove the notification row ready to be used by notifier controller.
     * @param AmosNotify $notifyModule
     * @param Newsletter $newsletter
     * @return bool
     */
    public static function removeNewsletterNotification($notifyModule, $newsletter)
    {
        if (is_null($notifyModule)) {
            $notifyModule = AmosNotify::instance();
            if (is_null($notifyModule)) {
                throw new NewsletterException('Notify module not found');
            }
        }
        
        /** @var Notification $notificationModel */
        $notificationModel = $notifyModule->createModel('Notification');
        
        /** @var Notification[] $notifications */
        $notifications = $notificationModel::find()->andWhere([
            'channels' => NotificationChannels::CHANNEL_NEWSLETTER,
            'content_id' => $newsletter->id,
            'class_name' => $newsletter::className(),
            'processed' => 0
        ])->all();
        $ok = true;
        foreach ($notifications as $notification) {
            /** @var Notification $notification */
            if (!is_null($notification)) {
                $notification->delete();
                if ($notification->hasErrors()) {
                    $ok = false;
                }
            }
        }
        return $ok;
    }
    
    /**
     * This methods returns all the configured models as an array of NewsletterContentsConf objects.
     * @param AmosNotify|null $notifyModule
     * @param bool $withOrder
     * @return NewsletterContentsConf[]
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAllNewsletterContentsConfs($notifyModule = null, $withOrder = true)
    {
        if (is_null($notifyModule)) {
            $notifyModule = AmosNotify::instance();
        }
        /** @var NewsletterContentsConf $newsletterContentsConfModel */
        $newsletterContentsConfModel = $notifyModule->createModel('NewsletterContentsConf');
        /** @var ActiveQuery $query */
        $query = $newsletterContentsConfModel::find();
        $query->indexBy('id');
        if ($withOrder) {
            $query->orderBy(['order' => SORT_ASC]);
        }
        /** @var NewsletterContentsConf[] $newsletterContentsConfs */
        $newsletterContentsConfs = $query->all();
        return $newsletterContentsConfs;
    }
    
    /**
     * This methods returns an array of the configured models as objects.
     * @param AmosNotify|null $notifyModule
     * @param bool $withOrder
     * @return Record|NewsletterInterface[]
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAllNewsletterContentsModels($notifyModule = null, $withOrder = true)
    {
        $newsletterContentsConfs = self::getAllNewsletterContentsConfs($notifyModule, $withOrder);
        $newsletterContentsModels = [];
        foreach ($newsletterContentsConfs as $newsletterContentsConf) {
            $newsletterContentsModels[$newsletterContentsConf->id] = $newsletterContentsConf->getContentConfModel();
        }
        return $newsletterContentsModels;
    }
    
    /**
     * This methods returns an array with the published statuses of the configured models.
     * @param AmosNotify|null $notifyModule
     * @param bool $withOrder
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAllConfModelsPublishedStatuses($notifyModule = null, $withOrder = true)
    {
        $newsletterContentsModels = NewsletterUtility::getAllNewsletterContentsModels($notifyModule, $withOrder);
        $publishedStatusesByContentConfModel = [];
        foreach ($newsletterContentsModels as $newsletterContentsConfId => $newsletterContentsModel) {
            $publishedStatusesByContentConfModel[$newsletterContentsConfId] = $newsletterContentsModel->newsletterPublishedStatus();
        }
        return $publishedStatusesByContentConfModel;
    }
}
