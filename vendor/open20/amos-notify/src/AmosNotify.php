<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager;

/**
 * Plugin per la gestione delle notifiche.
 *
 * Si basa su una Behavior (NotifyBehavior) che aggiunge funzionalità al modello con gli Events:
 *
 *      ActiveRecord::EVENT_AFTER_INSERT
 *      ActiveRecord::EVENT_AFTER_UPDATE
 *
 * quando un record del modello viene inserito o aggiornato, viene aggiunto un record nella coda delle notifica (Notification)
 * per ogni canale configurato nella Behavior (default CHANNEL_ALL).
 *
 *      ActiveRecord::EVENT_AFTER_FIND
 * ogni volta che viene chiamato questo evento per il modello la Behavior inserisce o aggiorna un record per il canale CHANNEL_READ
 * nella coda dei letti (NotificationsRead)
 *
 *      CrudController::AFTER_FINDMODEL_EVENT (evento scatenato dal metodo findModel del CrudController)
 * ogni volta che viene chiamato questo evento per il modello la Behavior inserisce o aggiorna un record per il canale CHANNEL_READ_DETAIL
 * nella coda dei letti (NotificationsRead)
 *
 * Canali di notifica disponibili, possono essere configurati tramite il parametro 'channels' della NotifyBehavior es:
 *
 *      'channels' => [NotificationChannels::CHANNEL_MAIL,NotificationChannels::CHANNEL_IMMEDIATE_MAIL ,NotificationChannels::CHANNEL_ALL]
 *
 *      se presente il valore NotificationChannels::CHANNEL_ALL ha la precedenza inserendo tutti i canali disponibili.
 *
 * CHANNEL_MAIL             -- Canale per la gestione notifiche per mail (TODO)
 * CHANNEL_IMMEDIATE_MAIL   -- Canale per la gestione notifiche per mail immediata (TODO)
 * CHANNEL_UI               -- Canale per la gestione notifiche da User Interface (TODO)
 * CHANNEL_SMS              -- Canale per la gestione notifiche per SMS (TODO)
 * CHANNEL_READ             -- Canale per la gestione notifiche da ActiveRecord::EVENT_AFTER_FIND
 * CHANNEL_READ_DETAIL      -- Canale per la gestione notifiche da CrudController::AFTER_FINDMODEL_EVENT
 * CHANNEL_FAVOURITES       -- Canale per la gestione dei preferiti
 * CHANNEL_ALL              -- Tutti i Canali
 */

use open20\amos\core\module\AmosModule;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\base\NotifierRepository;
use open20\amos\notificationmanager\base\NotifyWidget;
use open20\amos\notificationmanager\listeners\NotifyWorkflowListener;
use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use open20\amos\notificationmanager\utility\NotifyUtility;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\base\Event;
use yii\db\ActiveQuery;
use yii\log\Logger;

/**
 * Class AmosNotify
 * @package open20\amos\notificationmanager
 */
class AmosNotify extends AmosModule implements \yii\base\BootstrapInterface, NotifyWidget
{
    public $batchFromDate; // format 'yyyy-mm-dd'
    public $defaultSchedule = NotificationsConfOpt::EMAIL_DAY;
    public $confirmEmailNotification = false;
    /**
     *  disable notification for default behavior.
     *  enable notification only with post parameter: saveNotificationSendEmail = 1
     */
    public $disableDefaultBehaviorClasses = [
    ];

    public $orderEmailSummary = [
        'open20\amos\events\models\Event',
        'open20\amos\news\models\News',
        'open20\amos\partnershipprofiles\models',
        'open20\amos\discussioni\models\DiscussioniTopic',
        'open20\amos\sondaggi\models\Sondaggi',
    ];

    /** @var bool */
    public $enableNotificationContentLanguage = false;
    public $sleepingUserDayLimit = 30; // If the user is inactive for those days he is sleeping
    public $contentsLimit = 2;         // max items foreach content in email to userNotification
    public $usersLimit = 5;            // max users in email to userNotification (es max contatti, max visits)

    /**
     * @var null |string
     * [
     *      'open20\amos\admin\models\UserProfile' => '@common/mail/notify_validation/validator_user_profile',
     *      'open20\amos\news\models\News' => '@common/mail/notify_validation/validator_news',
     * ]
     *
     */
    public $viewPathEmailNotifyValidator = [];

    /**
     * @var null |string
     * [
     *      'open20\amos\admin\models\UserProfile' => '@common/mail/notify_validation/validated_user_profile',
     *      'open20\amos\news\models\News' => '@common/mail/notify_validation/validated_news',
     * ]
     */
    public $viewPathEmailNotifyValidated = [];

    /**
     *
     * @var boolean $enableLegacyNotify
     */
    public $enableLegacyNotify = false;

    private static $notifyworkflowlistener;
    private static $registerEvent        = false;

    /**
     * @var array $customIconPlugins
     */
    public $customIconPlugins = [];

    public $contentToNotNotify = [];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\notificationmanager\controllers';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        self::$notifyworkflowlistener = new NotifyWorkflowListener();
        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "notify";
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@open20/amos/notificationmanager/commands', __DIR__ . '/commands/');
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        $this->orderEmailSummary = array_unique($this->orderEmailSummary);
    }

    /**
     * @param \yii\console\Application $app
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'open20\amos\notificationmanager\commands';
        } else {
            if (self::$registerEvent == false) {
                self::$registerEvent = true;
                Event::on(
                    Record::className(), SimpleWorkflowBehavior::EVENT_AFTER_CHANGE_STATUS,
                    [self::$notifyworkflowlistener, 'afterChangeStatus']
                );
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'ChangeStatusEmail' => __NAMESPACE__ . '\\' . 'models\ChangeStatusEmail',
            'Newsletter' => __NAMESPACE__ . '\\' . 'models\Newsletter',
            'NewsletterContents' => __NAMESPACE__ . '\\' . 'models\NewsletterContents',
            'NewsletterContentsConf' => __NAMESPACE__ . '\\' . 'models\NewsletterContentsConf',
            'NewsletterSearch' => __NAMESPACE__ . '\\' . 'models\search\NewsletterSearch',
            'Notification' => __NAMESPACE__ . '\\' . 'models\Notification',
            'NotificationChannels' => __NAMESPACE__ . '\\' . 'models\NotificationChannels',
            'NotificationConf' => __NAMESPACE__ . '\\' . 'models\NotificationConf',
            'NotificationconfNetwork' => __NAMESPACE__ . '\\' . 'models\NotificationconfNetwork',
            'NotificationContentLanguage' => __NAMESPACE__ . '\\' . 'models\NotificationContentLanguage',
            'NotificationLanguagePreferences' => __NAMESPACE__ . '\\' . 'models\NotificationLanguagePreferences',
            'NotificationsConfOpt' => __NAMESPACE__ . '\\' . 'models\NotificationsConfOpt',
            'NotificationSendEmail' => __NAMESPACE__ . '\\' . 'models\NotificationSendEmail',
            'NotificationsRead' => __NAMESPACE__ . '\\' . 'models\NotificationsRead',
            'NotificationsSent' => __NAMESPACE__ . '\\' . 'models\NotificationsSent',
            'NotificationConfContent' => __NAMESPACE__ . '\\' . 'models\NotificationConfContent',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [];
    }

    /**
     * Same as calling AmosNotify::t('amosnotify', ...$args)
     * @return string
     */
    public static function txt($txt, ...$args)
    {
        return static::t('amosnotify', $txt, ...$args);
    }

    /**
     * Same as calling AmosNotify::tHtml('amosnotify', ...$args)
     * @return string
     */
    public static function txtHtml($txt, ...$args)
    {
        return static::tHtml('amosnotify', $txt, ...$args);
    }

    /**
     * @param int $uid
     * @param string $class_name
     * @param ActiveQuery|null $externalquery
     * @param NotificationChannels $channel
     */
    public function notificationOff($uid, $class_name, $externalquery = null, $channel)
    {
        try {
            $repository = new NotifierRepository();
            $repository->notificationOff($uid, $class_name, $externalquery, $channel);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param int $uid
     * @param string $class_name
     * @param ActiveQuery|null $externalquery
     * @param NotificationChannels $channel
     */
    public function notificationOn($uid, $class_name, $externalquery = null, $channel)
    {
        try {
            $repository = new NotifierRepository();
            $repository->notificationOn($uid, $class_name, $externalquery, $channel);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param int $uid
     * @param string $class_name
     * @param ActiveQuery|null $externalquery
     * @return false|int|null|string
     */
    public function countNotRead($uid, $class_name, $externalquery = null)
    {
        $result = 0;
        try {
            $repository = new NotifierRepository();
            $result = $repository->countNotRead($uid, $class_name, $externalquery);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $result;
    }

    /**
     * @param $model
     * @param int|null $uid
     * @return bool
     */
    public function modelIsRead($model, $uid = null)
    {
        $result = false;
        try {
            $repository = new NotifierRepository();
            $result = $repository->modelIsRead($model, $uid);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $result;
    }

    /**
     * @param string $modelClassName
     * @param int $channel
     * @param string $type
     * @return array|bool
     */
    public static function manageNewChannelNotifications($modelClassName, $channel, $type)
    {
        $retval = false;
        try {
            /** @var NotificationChannels $notificationChannel */
            $notificationChannel = AmosNotify::instance()->createModel('NotificationChannels');
            $retval = $notificationChannel->manageNewChannelNotifications($modelClassName, $channel, $type);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $retval;
    }

    /**
     * @param int $uid
     * @param string $class_name
     * @param int $contentId
     * @return bool
     */
    public function favouriteOn($uid, $class_name, $contentId)
    {
        $ok = true;
        try {
            $repository = new NotifierRepository();
            $ok = $repository->favouriteOn($uid, $class_name, $contentId);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $ok;
    }

    /**
     * @param int $uid
     * @param string $class_name
     * @param int $contentId
     * @return bool
     */
    public function favouriteOff($uid, $class_name, $contentId)
    {
        $ok = true;
        try {
            $repository = new NotifierRepository();
            $ok = $repository->favouriteOff($uid, $class_name, $contentId);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $ok;
    }

    /**
     * @param $model
     * @param int|null $uid
     * @return bool
     */
    public function isFavorite($model, $uid = null)
    {
        $result = false;
        try {
            $repository = new NotifierRepository();
            $result = $repository->isFavorite($model, $uid);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $result;
    }

    /**
     * @param $class_name
     * @param int|null $uid
     * @return bool
     */
    public function getAllFavourites($class_name, $uid)
    {
        $result = false;
        try {
            $repository = new NotifierRepository();
            $result = $repository->getAllFavourites($class_name, $uid);
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return $result;
    }

    /**
     * The method save the notification configuration.
     * @param int $userId
     * @param int $emailFrequency
     * @param int $smsFrequency
     * @return bool
     */
    public function saveNotificationConf($userId, $emailFrequency = 0, $smsFrequency = 0, $params = [])
    {
        $notifyUtility = new NotifyUtility();
        return $notifyUtility->saveNotificationConf($userId, $emailFrequency, $smsFrequency, $params);
    }

    /**
     * This method set the user default notifications configurations.
     * @param int $userId
     * @return bool
     */
    public function setDefaultNotificationsConfs($userId)
    {
        $notifyUtility = new NotifyUtility();
        return $notifyUtility->setDefaultNotificationsConfs($userId);
    }

    public function contactAccepted($user, $invitedUser)
    {
        $notifyUtility = new NotifyUtility();
        $notifyUtility->contactAccepted($user, $invitedUser);
    }
}
