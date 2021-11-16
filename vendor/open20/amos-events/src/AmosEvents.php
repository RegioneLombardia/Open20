<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

namespace open20\amos\events;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\events\models\Event;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\widgets\icons\WidgetIconEvents;
use open20\amos\events\widgets\icons\WidgetIconEventsCreatedBy;
use open20\amos\events\widgets\icons\WidgetIconEventsManagement;
use open20\amos\events\widgets\icons\WidgetIconEventsToPublish;
use open20\amos\events\widgets\icons\WidgetIconEventTypes;
use open20\amos\events\widgets\InviteUserToEventWidget;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * Class AmosEvents
 * @package open20\amos\events
 */
class AmosEvents extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BootstrapInterface
{
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\events\controllers';

    public $newFileMode = 0666;
    public $name = 'Events';

    /**
     * If this attribute is true the validation of the publication date is active
     * @var boolean
     */
    public $validatePublicationDateEnd = true;
    /**
     * @var bool|false $enableGoogleMap
     */
    public $enableGoogleMap = true;
    /**
     * @var bool|false $hidePubblicationDate
     */
    public $enableInvitationManagement = true;
    /**
     * @var bool|false $hidePubblicationDate
     */
    public $hidePubblicationDate = false;

    /**
     * This param enable or disable the export button in lists.
     * @var bool $enableExport
     */
    public $enableExport = true;

    /**
     * This param enables enables multiple recording for the same event.
     * @var bool $enableExport
     */
    public $multipleRecording = false;

    /**
     * @var array $eventsRequiredFields
     */
    public $eventsRequiredFields = [
        'title',
        'summary',
        'description',
        'begin_date_hour',
        'event_type_id',
        'publish_in_the_calendar',
        'event_management',
        'event_commentable',
    ];

    /**
     * @var bool $eventLengthRequired If true enable the required validator on field "length"
     */
    public $eventLengthRequired = false;

    /**
     * @var bool $eventMURequired If true enable the required validator on length measurement unit
     */
    public $eventMURequired = false;

    /**
     * @var bool $forceEventCommentable
     */
    public $forceEventCommentable = '1';

    /**
     * @inheritdoc
     */
    public $db_fields_translation = [
        [
            'namespace' => 'open20\amos\events\models\EventType',
            'attributes' => ['title'],
            'category' => 'amosevents',
        ],
    ];

    public $viewPathEmailSummary = [
        'open20\amos\events\models\Event' => '@vendor/open20/amos-events/src/views/email/notify_summary'
    ];

    public $viewPathEmailSummaryTitle = [
        'open20\amos\events\models\Event' => '@vendor/open20/amos-events/src/views/email/notify_summary_title'
    ];

    /**
     * @var bool $enableSeatsManagement
     */
    public $enableSeatsManagement = true;

    /**
     * @var bool $enableAutoInviteUsers If true enable the auto invite of the event recipients when the event is published.
     */
    public $enableAutoInviteUsers = false;

    public $tempPath = '@backend/web/ticket_download';

    /**
     * @var bool $enableGdpr If true enable the GDPR in all the plugin.
     */
    public $enableGdpr = true;

    /**
     * @var bool $enableCommunitySections If true enable the community sections in all the plugin.
     */
    public $enableCommunitySections = true;

    /**
     * @var bool $actionCreatedByOnlyViewGrid If true the only list view for the action created by is the grid view.
     */
    public $actionCreatedByOnlyViewGrid = false;

    /**
     * @var bool
     */
    public $enableCalendarsManagement = false;

    /**
     * @var bool $forceEventSubscription if true the user is immediatly subscribed to event
     */
    public $forceEventSubscription = false;

    /**
     * @var bool $viewEventSignupLinkInForm If true enable a read only field that show the event sign-up generic link
     */
    public $viewEventSignupLinkInForm = false;

    /**
     * @var array
     */
    public $hideViewTabs = [];

    /**
     * @var bool
     */
    public $enableNewWizard = false;

    /**
     * @var array
     * [
     *   'id' => 1,
     *   'role' => 'SUPER_USER_EVENT',
     *   'roleAdmin' => 'ADMIN'
     */
    public $groupReferentAdministration = [];

    /**
     * @var array
     * [
     *  'mailup_list_id' => 1,
     *  'dynamic_fields' => [ ID_DYNAMIC_FIELD => USER_PROFILE_ATTRIBUTE]
     * ]
     */
    public $mailup_configurations = [];

    /**
     *  Enable Event parent (it is a container for multiple events)
     * @var bool
     */
    public $enableEventContainer = true;

    /**
     *  Set istat_regioni_id if you want only comuni e province of one region
     * @var integer|null
     */
    public $showOnlyRegionInWizard = null;

    /**
     *  Set istat_regioni_id if you want only comuni e province of one region
     * @var integer|null
     *      'enabled' => true,
            'courseid' => 9,
            'role' => 'CANDIDATO_OPERATORE'
     */
    public $operatorCandidate = [
    ];

    /**
     * @var bool
     */
    public $restrictionRegisterDate = true;

    /**
     * @var integer|null
     */
    public $patronage_luya_nav_id = null;
    /**
     * @var string
     */
    public $default_image_patronage = '/img/default_img.jpg';

    /**
     * @var string
     */
    public  $user_profile_privacy_attr;

    /**
     * @var string
     */
    public $url_download_app_mobile;

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        return 'calendar';
    }

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'open20\amos\events\commands';
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
        // custom initialization code goes here
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, ["params" => $this->params]));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconEvents::className(),
            WidgetIconEventTypes::className(),
            WidgetIconEventsCreatedBy::className(),
            WidgetIconEventsToPublish::className(),
            WidgetIconEventsManagement::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'Event' => __NAMESPACE__ . '\\' . 'models\Event',
            'EventAccreditationList' => __NAMESPACE__ . '\\' . 'models\EventAccreditationList',
            'EventInvitation' => __NAMESPACE__ . '\\' . 'models\EventInvitation',
            'EventInvitationPartner' => __NAMESPACE__ . '\\' . 'models\EventInvitationPartner',
            'EventInvitationsUpload' => __NAMESPACE__ . '\\' . 'models\EventInvitationsUpload',
            'EventLengthMeasurementUnit' => __NAMESPACE__ . '\\' . 'models\EventLengthMeasurementUnit',
            'EventMembershipType' => __NAMESPACE__ . '\\' . 'models\EventMembershipType',
            'EventParticipantCompanion' => __NAMESPACE__ . '\\' . 'models\EventParticipantCompanion',
            'EventParticipantCompanionDynamic' => __NAMESPACE__ . '\\' . 'models\EventParticipantCompanionDynamic',
            'EventSeats' => __NAMESPACE__ . '\\' . 'models\EventSeats',
            'EventType' => __NAMESPACE__ . '\\' . 'models\EventType',
            'EventTypeContext' => __NAMESPACE__ . '\\' . 'models\EventTypeContext',
            'FormAssignSeat' => __NAMESPACE__ . '\\' . 'models\FormAssignSeat',
            'RegisterGroupForm' => __NAMESPACE__ . '\\' . 'models\RegisterGroupForm',
            'EventAccreditationListSearch' => __NAMESPACE__ . '\\' . 'models\search\EventAccreditationListSearch',
            'EventSearch' => __NAMESPACE__ . '\\' . 'models\search\EventSearch',
            'EventTypeSearch' => __NAMESPACE__ . '\\' . 'models\search\EventTypeSearch',
            'EventContainer' => __NAMESPACE__ . '\\' . 'models\EventContainer',
            'EventCategory' => __NAMESPACE__ . '\\' . 'models\EventCategory',
        ];
    }

    /**
     * @param \Google_Service_Calendar $serviceGoogle
     * @param string $calendarId
     * @param string $message
     * @return string $message
     */
    public function synchronizeEvents($serviceGoogle, $calendarId, $message = '')
    {
        /** @var EventSearch $eventSearch */
        $eventSearch = $this->createModel('EventSearch');
        $all = $eventSearch->buildQuery([], 'all')->select('event.id')->column();
        $createdBy = $eventSearch->buildQuery([], 'created-by')->select('event.id')->column();
        /** @var Event[] $events */
        $events = $eventSearch->baseSearch([])->andWhere([
            'event.id' => ArrayHelper::merge($all, $createdBy)
        ])->all();
        $eventList = $serviceGoogle->events->listEvents($calendarId);
        $items = $eventList->getItems();
        $insertAll = false;
        if (empty($items)) {
            $insertAll = true;
            $isUpdate = false;
        } else {
            $isUpdate = true;
        }

        $insertCount = 0;
        $updatedCount = 0;
        foreach ($events as $event) {
            if ($event->begin_date_hour) {
                $eventId = $event->getGoogleEventId();
                $eventCalendar = null;
                if (!$insertAll) {
                    try {
                        $eventCalendar = $serviceGoogle->events->get($calendarId, $eventId);
                        $isUpdate = true;
                    } catch (\Google_Service_Exception $ex) {
                        $isUpdate = false;
                    }
                }
                $eventCalendar = $event->getGoogleEvent($eventCalendar);
                try {
                    if ($insertAll || !$isUpdate) {
                        $serviceGoogle->events->insert($calendarId, $eventCalendar);
                        $insertCount++;
                    } else {
                        $serviceGoogle->events->update($calendarId, $eventId, $eventCalendar);
                        $updatedCount++;
                    }
                } catch (\Google_Service_Exception $e) {
                    $message .= '<br/>' . $e->getMessage() . '<br/>';
                }
            }
        }
        if ($insertCount) {
            $message .= '<br/>' . AmosEvents::t('amosevents', 'Events added:') . ' ' . $insertCount;
        }
        if ($updatedCount) {
            $message .= '<br/>' . AmosEvents::t('amosevents', 'Events updated:') . ' ' . $updatedCount;
        }

        return $message;
    }

    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\EventSearch';
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\Event';
    }

    /**
     * Same as calling AmosEvents::t('amosevents', ...$args)
     * @return string
     */
    public static function txt($txt, ...$args)
    {
        return self::t('amosevents', $txt, ...$args);
    }

    /**
     * Same as calling AmosEvents::tHtml('amosevents', ...$args)
     * @return string
     */
    public static function txtHtml($txt, ...$args)
    {
        return self::tHtml('amosevents', $txt, ...$args);
    }

    /**
     * @param \open20\amos\admin\models\UserProfile $model
     * @return string
     * @throws \Exception
     */
    public function getInviteUserToEventWidget($model)
    {
        return InviteUserToEventWidget::widget(['model' => $model]);
    }

    /**
     * @return bool|string
     */
    public function getTempPath()
    {
        return \Yii::getAlias($this->tempPath);
    }
}
