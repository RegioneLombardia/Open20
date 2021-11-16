<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models
 * @category   CategoryName
 */

namespace open20\amos\events\models;

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\attachments\models\File;
use open20\amos\core\utilities\DuplicateContentUtility;

use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\events\models\search\EventSearch;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\comments\models\CommentInterface;
use open20\amos\community\models\CommunityContextInterface;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\behaviors\SoftDeleteByBehavior;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use open20\amos\events\i18n\grammar\EventGrammar;
use open20\amos\events\utility\EventsUtility;
use open20\amos\events\widgets\icons\WidgetIconEvents;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use yii\base\Behavior;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use open20\amos\seo\interfaces\SeoModelInterface;
use open20\amos\admin\models\TokenGroup;

/**
 * Class Event
 * This is the model class for table "event".
 *
 * @property-read string $completeAddress
 *
 * @package open20\amos\events\models
 */
class Event extends \open20\amos\events\models\base\Event implements ContentModelInterface, CommunityContextInterface, CommentInterface, ViewModelInterface, SeoModelInterface
{
    /**
     * Constants for community roles
     */
    const EVENT_MANAGER = 'EVENT_MANAGER';
    const EVENT_PARTICIPANT = 'EVENT_PARTICIPANT';
    const EVENTS_CHECK_IN = 'EVENTS_CHECK_IN';


    const ROOT_TAG_PREFERENCE_CENTER = 'root_preference_center';
    const ROOT_TAG_CUSTOM_EVENTS = 'root_events_tag_custom';

    const LOG_TYPE_SUBSCRIBE_EVENT = 'subscribe_event';
    const LOG_TYPE_INVITATION_SENT = 'invitation_sent';
    const LOG_TYPE_UNSUBSCRIBE_EVENT = 'unsubscribe_event';
    const LOG_TYPE_ATTENDANT_TO_EVENT = 'attendant_to_event';
    const LOG_TYPE_COMMUNICATION_SENT = 'communication_sent';

    /**
     * @var string
     */
    public $begin_date_hour_from;

    /**
     * @var string $begin_date_hour_to
     */
    public $begin_date_hour_to;

    /**
     * @var string $end_date_hour_from
     */
    public $end_date_hour_from;

    /**
     * @var string $end_date_hour_to
     */
    public $end_date_hour_to;


    /**
     * For wizard
     * @var
     */
    public $beginDate;
    public $beginHour;
    public $endDate;
    public $endHour;
    public $preferencesTags;
    public $customTags;
    public $customTagsDefault;
    public $limitedSeats;

    /**
     * @var $eventLogo
     */
    private $eventLogo;

    /**
     * @var bool $bypassEventLogoValidation
     */
    public $bypassEventLogoValidation = false;

    /**
     * @var $eventAttachments
     */
    public $eventAttachments;

    /**
     * @var $eventAttachmentsForItemView
     */
    public $eventAttachmentsForItemView;

    /**
     * @var $location
     */
    public $location;

    /**
     * @var $landingHeader
     */
    private $landingHeader;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());
            if (!is_null($moduleEvents)) {
                if ($moduleEvents->hidePubblicationDate) {
                    $this->registration_date_end = '9999-12-31';
                }
                $this->registration_date_begin = date('Y-m-d');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'title',
        ];
    }

    /**
     * @inheritdoc
     */
    public function getModelImage()
    {
        return $this->eventLogo;
    }


    /**
     * @inheritdoc
     */
    public function getSchema()
    {
//        $event = new \open20\amos\events\models\schemaorg\Event();
//        $publisher = new \simialbi\yii2\schemaorg\models\Organization();
////        $author = new \simialbi\yii2\schemaorg\models\Person();
//        $userProfile = UserProfile::find()->andWhere(['user_id' => $this->created_by])->one();
//        if (!is_null($userProfile)) {
//            $logo = new \simialbi\yii2\schemaorg\models\ImageObject();
//            $publisher->name = $userProfile->nomeCognome;
//            $img = $userProfile->userProfileImage;
//            if (!is_null($img)) {
//                $logo->url = $img->getWebUrl(false, true);
//            }
//            $publisher->logo = $logo;
////            $author->name = $userProfile->nomeCognome;
//        }
//        $image = new \simialbi\yii2\schemaorg\models\ImageObject();
//        $newsImage = $this->eventLogo;
//        if (!empty($newsImage)) {
//            $image->url = $newsImage->getWebUrl(false, true);
//        }
//        $event->name = $this->title;
//        $event->about = $this->summary;
//        $event->description = $this->description;
//        $event->image = $image;
//        $event->startDate = $this->begin_date_hour;
//        $event->endDate = $this->end_date_hour;
//        $event->url = EventsUtility::getUrlLanding($this);
//        $event->location = $this->getFullLocationString();
//        $event->organizer = $publisher;
////        $event->eventSchedule = $this->eventLanding->schedule;
//
////        $news->author = $author;
////        $news->datePublished = $this->publication_date_begin;
////        $news->headline = substr($this->getShortDescription(), 0, 110);
////        $news->image = $image;
////        $news->publisher = $publisher;
//
//        \simialbi\yii2\schemaorg\helpers\JsonLDHelper::add($event);
//
//        return \simialbi\yii2\schemaorg\helpers\JsonLDHelper::render();

    }

    /**
     * @inheritdoc
     */
    public function getFacilitatorRole()
    {
        return "FACILITATOR";
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->eventLogo = $this->getEventLogo();
        $this->eventAttachments = $this->getEventAttachments()->one();
        $this->eventAttachmentsForItemView = $this->getEventAttachments()->all();
        $this->landingHeader = $this->getLandingHeader();
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = ArrayHelper::merge(
            parent::scenarios(), $this->wizardScenarios()
        );
        return $scenarios;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::className(),
                    'titleAttribute' => 'title',
                    'descriptionAttribute' => 'description',
                    'imageAttribute' => 'eventLogo',
                    'defaultOgType' => 'article',
                    'schema' => 'NewsArticle'
                ],
                'NotifyBehavior' => [
                    'class' => NotifyBehavior::className(),
                    'conditions' => []
                ]
            ]);
    }

    /**
     * All creation event wizard behaviors.
     * @return array
     */
    private function wizardScenarios()
    {
        return [
            self::SCENARIO_INTRODUCTION => [
                'event_type_id'
            ],
            self::SCENARIO_DESCRIPTION => [
                'title',
                'summary',
                'description',
                'eventLogo',
                'file',
                'event_location',
                'event_address',
                'event_address_house_number',
                'event_address_cap',
                'city_location_id',
                'province_location_id',
                'country_location_id',
                'begin_date_hour',
                'length',
                'length_mu_id',
                'end_date_hour',
                'eventTypeId',
                'event_commentable'
            ],
            self::SCENARIO_ORGANIZATIONALDATA => [
                'publish_in_the_calendar',
                'event_management',
                'event_membership_type_id',
                'seats_available',
                'paid_event',
                'registration_limit_date'
            ],
            self::SCENARIO_PUBLICATION => [
                'publication_date_begin',
                'publication_date_end',
            ],
            self::SCENARIO_SUMMARY => [
                'status',
            ],
            self::SCENARIO_ORG_HIDE_PUBBLICATION_DATE => [
                'publish_in_the_calendar',
                'event_management',
                'event_membership_type_id',
                'seats_available',
                'paid_event',
                'registration_limit_date'
            ]
        ];
    }

    /**
     * Getter for $this->eventLogo;
     * @return \yii\db\ActiveQuery
     */
    public function getEventLogo()
    {
        if (empty($this->eventLogo)) {
            $this->eventLogo = $this->hasOneFile('eventLogo')->one();
        }
        return $this->eventLogo;
    }

    /**
     *
     * @param type $image
     */
    public function setEventLogo($image)
    {
        $this->eventLogo = $image;
    }

    /**
     * Getter for $this->landingHeader;
     * @return \yii\db\ActiveQuery
     */
    public function getLandingHeader()
    {
        if (empty($this->landingHeader)) {
            $this->landingHeader = $this->hasOneFile('landingHeader')->one();
        }
        return $this->landingHeader;
    }

    /**
     *
     * @param type $image
     */
    public function setLandingHeader($image)
    {
        $this->landingHeader = $image;
    }

    /**
     * Getter for $this->eventAttachments;
     * @return \yii\db\ActiveQuery
     */
    public function getEventAttachments()
    {
        $query = $this->hasMultipleFiles('eventAttachments');
        $query->multiple = false;
        return $query;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());

        $rules = ArrayHelper::merge(parent::rules(),
            [
                [['eventAttachments'], 'file', 'maxFiles' => 0],
                [['eventLogo'], 'file', 'extensions' => 'jpeg, jpg, png, gif'],
                ['seats_available', 'canChangeStatus'],
                ['seats_available', 'checkAvailableSeats'],
                // [['eventLogo'], 'required', 'when' => function ($model) {
                //     /** @var \open20\amos\events\models\Event $model */
                //     if ($this->bypassEventLogoValidation) {
                //         return false;
                //     }
                //     if (is_null($this->eventType)) {
                //         return false;
                //     }
                //     return ($model->eventType->logoRequested == 1 ? true : false);
                // }, 'whenClient' => "function (attribute, value) {
                //     return " . (!is_null($this->eventType) ? $this->eventType->logoRequested : 0) . ";
                // }"],
//            [['registration_date_begin', 'registration_date_end'], 'required', 'when' => function($model) {
//                return (bool)($this->eventType && $this->eventType->event_type != EventType::TYPE_INFORMATIVE);
//            }, 'whenClient' => 'function(attribute, value) { return ' . ($this->eventType && $this->eventType->event_type != EventType::TYPE_INFORMATIVE ? 'true' : 'false') . '}'],
                ['show_community', 'integer'],
                ['show_on_frontend', 'integer'],
                ['has_tickets', 'integer'],
                ['has_qr_code', 'integer'],
                ['gdpr_question_1', 'string'],
                ['gdpr_question_2', 'string'],
                ['gdpr_question_3', 'string'],
                ['gdpr_question_4', 'string'],
                ['gdpr_question_5', 'string'],
                // ['landingHeader', 'file', 'extensions' => 'jpeg, jpg, png, gif'],
            ]);


        if ((!empty($this->registration_date_begin) && !empty($this->registration_date_end))) {
            $rules = ArrayHelper::merge($rules,
                [
                    ['registration_date_begin', 'compare', 'compareAttribute' => 'registration_date_end', 'operator' => '<=',
                        'when' => function ($model) {
                            return (bool)($this->eventType && $this->eventType->event_type != EventType::TYPE_INFORMATIVE && !empty($this->registration_date_end));
                        }, 'whenClient' => 'function(attribute, value) { 
                        return false;
                        return ' . ($this->eventType && $this->eventType->event_type
                        != EventType::TYPE_INFORMATIVE ? 'true' : 'false') . '}'],
                    ['registration_date_end', 'compare', 'compareAttribute' => 'registration_date_begin', 'operator' => '>=',
                        'when' => function ($model) {
                            return (bool)($this->eventType && $this->eventType->event_type != EventType::TYPE_INFORMATIVE && !empty($this->registration_date_end));
                        }, 'whenClient' => 'function(attribute, value) { 
                        return false;
                        return ' . ($this->eventType && $this->eventType->event_type
                        != EventType::TYPE_INFORMATIVE ? 'true' : 'false') . '}'],
                    ['registration_date_begin', 'checkDate', 'when' => function ($model) {
                        return (bool)($this->eventType && $this->eventType->event_type != EventType::TYPE_INFORMATIVE);
                    }, 'whenClient' => 'function(attribute, value) { return ' . ($this->eventType && $this->eventType->event_type
                        != EventType::TYPE_INFORMATIVE ? 'true' : 'false') . '}'],
                    ['registration_date_end', 'checkDate', 'when' => function ($model) {
                        return (bool)($this->eventType && $this->eventType->event_type != EventType::TYPE_INFORMATIVE);
                    }, 'whenClient' => 'function(attribute, value) { return ' . ($this->eventType && $this->eventType->event_type
                        != EventType::TYPE_INFORMATIVE ? 'true' : 'false') . '}'],
                ]);
        }

        if ($moduleEvents->enableNewWizard) {
            $rules = ArrayHelper::merge($rules,
                [
                    [['event_category_id', 'event_location_id', 'beginDate', 'endDate', 'endHour', 'beginHour'], 'required'],
                    ['description', 'string', 'max' => 150],
                    ['summary', 'string', 'max' => 50]
                ]
            );
        }
        return $rules;
    }

    /**
     *
     */
    public function checkAvailableSeats()
    {
        if (!$this->seats_management) {
            if ($this->seats_available <= 0 && (!empty($this->eventType) && $this->eventType->limited_seats)) {
                $this->addError('seats_available', AmosEvents::t('amosevents', "Il numero di posti disponibili deve essere maggiore di zero"));
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            [
                'eventLogo' => AmosEvents::t('amosevents', 'Logo'),
                'begin_date_hour_from' => AmosEvents::t('amosevents', 'From begin date and hour'),
                'begin_date_hour_to' => AmosEvents::t('amosevents', 'To begin date and hour'),
                'end_date_hour_from' => AmosEvents::t('amosevents', 'From end date and hour'),
                'end_date_hour_to' => AmosEvents::t('amosevents', 'To end date and hour'),
                'landingHeader' => AmosEvents::t('amosevents', '#landing_header_label'),
                'has_tickets' => AmosEvents::t('amosevents', '#has_tickets_label'),
                'has_qr_code' => AmosEvents::t('amosevents', '#has_qr_code_label'),
                'gdpr_question_1' => AmosEvents::t('amosevents', '#gdpr_question_1_label'),
                'gdpr_question_2' => AmosEvents::t('amosevents', '#gdpr_question_2_label'),
                'gdpr_question_3' => AmosEvents::t('amosevents', '#gdpr_question_3_label'),
                'gdpr_question_4' => AmosEvents::t('amosevents', '#gdpr_question_4_label'),
                'gdpr_question_5' => AmosEvents::t('amosevents', '#gdpr_question_5_label'),
            ]);
    }

    /**
     * @param $attribute
     */
    public function canChangeStatus($attribute)
    {
        if ($this->isSeatManagement()) {
            $eventFromDB = Event::findOne($this->id);
            $isAlreadyPublished = false;
            if ($eventFromDB->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) {
                $isAlreadyPublished = true;
            }
            if (!$isAlreadyPublished) {
                if ($this->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED || $this->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST) {
                    if ($this->getEventSeats()->count() == 0) {
                        /** @var Event $eventModel */
                        $eventModel = $this->eventsModule->createModel('Event');
                        $eventOnDB = $eventModel::findOne($this->id);
                        if ($eventOnDB->seats_management) {
                            $this->addError($attribute,
                                AmosEvents::t("amosevents",
                                    "E' necessario effettuare l'importazione dei posti tramite excel"));
                        } else {
                            $this->addError($attribute,
                                AmosEvents::t("amosevents",
                                    "E' necessario salvare in bozza prima di effettuare l'importazione dei posti tramite excel"));
                        }
                    }
                }
            }
        }
    }

    /**
     * Funzione che crea gli eventi da visualizzare sulla mappa in caso di più eventi legati al singolo model
     * Andrà valorizzato il campo array a true nella configurazione della vista calendario nella index
     */
    public function getEvents()
    {
        return NULL; //da personalizzare
    }

    /**
     * Restituisce l'url per il calendario dell'attività
     */
    public function getEventUrl()
    {
        $module = \Yii::$app->getModule('events');
        $enableNewWizard = false;
        if ($module) {
            $enableNewWizard = $module->enableNewWizard;
        }

        if ($enableNewWizard) {
            return EventsUtility::getUrlLanding($this);
        }
//        $linkreferrer = \Yii::$app->request->url;
//        if (!empty($linkreferrer) && strpos($linkreferrer, 'dashboard') !== false) {
//            return \yii\helpers\Url::to(\Yii::$app->params['platform']['backendUrl'] . '/events/event/view?id=' . $this->id, true);
//        }
        return NULL; //da personalizzare magari con Yii::$app->urlManager->createUrl([]);
    }

    /**
     * Restituisce il colore associato all'evento
     */
    public function getEventColor()
    {
        if (is_null($this->eventType)) {
            return '';
        }
        return $this->eventType->color;
    }

    /**
     * Restituisce il titolo, possono essere anche più dati, associato all'evento
     */
    public function getEventTitle()
    {
        return $this->title;
    }

    /**
     * Restituisce un'immagine se associata al model
     */
    public function getAvatarUrl($dimension = 'small')
    {
        $url = '/img/img_default.jpg';
        //funzione da implementare
        return $url;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'title' => [
                'attribute' => 'title',
                'headerOptions' => [
                    'id' => 'title'
                ],
                'contentOptions' => [
                    'headers' => 'title'
                ]
            ],
            'description' => [
                'attribute' => 'description',
                'format' => 'html',
                'headerOptions' => [
                    'id' => 'description'
                ],
                'contentOptions' => [
                    'headers' => 'description'
                ]
            ],
            'begin_date_hour' => [
                'attribute' => 'begin_date_hour',
                'headerOptions' => [
                    'id' => 'begin'
                ],
                'contentOptions' => [
                    'headers' => 'begin'
                ]
            ],
            'end_date_hour' => [
                'attribute' => 'end_date_hour',
                'headerOptions' => [
                    'id' => 'end'
                ],
                'contentOptions' => [
                    'headers' => 'end'
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return "events/event/view";
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::EVENTS_WORKFLOW_STATUS_PUBLISHED;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::EVENTS_WORKFLOW_STATUS_DRAFT;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'EVENTS_VALIDATOR';
    }

    /**
     * @inheritdoc
     */
    public function getContextRoles()
    {
        $context_roles = [
            self::EVENT_MANAGER,
            self::EVENT_PARTICIPANT
        ];
        return $context_roles;
    }

    /**
     * @inheritdoc
     */
    public function getBaseRole()
    {
        return self::EVENT_PARTICIPANT;
    }

    /**
     * @inheritdoc
     */
    public function getManagerRole()
    {
        return self::EVENT_MANAGER;
    }

    public function getPriviledgedRoles()
    {
        return [
            self::EVENT_MANAGER,
            self::EVENTS_CHECK_IN,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRolePermissions($role)
    {
        switch ($role) {
            case self::EVENT_MANAGER:
                return ['CWH_PERMISSION_CREATE', 'CWH_PERMISSION_VALIDATE'];
                break;
            case self::EVENT_PARTICIPANT:
                return ['CWH_PERMISSION_CREATE'];
                break;
            default:
                return ['CWH_PERMISSION_CREATE'];
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function getCommunityModel()
    {
        return $this->community;
    }

    /**
     * @inheritdoc
     */
    public function getNextRole($role)
    {
        switch ($role) {
            case self::EVENT_MANAGER :
                return self::EVENT_PARTICIPANT;
                break;
            case self::EVENT_PARTICIPANT :
                return self::EVENT_MANAGER;
                break;
            default :
                return self::EVENT_PARTICIPANT;
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public function getPluginModule()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function getPluginController()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function getRedirectAction()
    {
        return 'view';
    }

    /**
     * @inheritdoc
     */
    public function getAdditionalAssociationTargetQuery($communityId)
    {
        /** @var ActiveQuery $communityUserMms */
        $communityUserMms = CommunityUserMm::find()->andWhere(['community_id' => $communityId]);
        return User::find()->andFilterWhere(['not in', 'id', $communityUserMms->select('user_id')]);
    }

    public function getPluginWidgetClassname()
    {
        return WidgetIconEvents::className();
    }

    /**
     * This method detach SoftDeleteByBehavior from the Event model.
     * @param string $className
     */
    public function detachBehaviorByClassName($className)
    {
        $behaviors = $this->getBehaviors();
        foreach ($behaviors as $index => $behavior) {
            /** @var Behavior $behavior */
            if ($behavior->className() == $className) {
                $this->detachBehavior($index);
            }
        }
    }

    /**
     * This method detach SoftDeleteByBehavior from the Event model.
     */
    public function detachEventSoftDeleteBehavior()
    {
        $this->detachBehaviorByClassName(SoftDeleteByBehavior::className());
    }

    /**
     * @inheritdoc
     */
    public function isCommentable()
    {
        return $this->event_commentable;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;

        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function getShortEventLocation()
    {
        return $this->__shortText($this->event_location, 255);
    }

    /**
     * @return string date begin of publication
     */
    public function getPublicatedFrom()
    {
        return $this->publication_date_begin;
    }

    /**
     * @return string date end of publication
     */
    public function getPublicatedAt()
    {
        return $this->publication_date_begin;
    }

    /**
     * @return \yii\db\ActiveQuery category of content
     */
    public function getCategory()
    {
        return $this->hasOne($this->eventsModule->model('EventType'), ['id' => 'event_type_id']);
    }

    public function getEventCategory()
    {
        return $this->hasOne($this->eventsModule->model('EventCategory'), ['id' => 'event_category_id']);
    }

    /**
     * @return string The url to view of this model
     */
    public function getFullViewUrl()
    {
        return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
    }

    /**
     * @return mixed
     */
    public function getGrammar()
    {
        return new EventGrammar();
    }

    /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [$this->getValidatedStatus()];
    }

    /**
     * @return string
     */
    public function getEventsImageUrl($size = 'original', $protected = true, $url = '/img/img_default.jpg')
    {
        $eventImage = $this->getEventLogo();
        if (!is_null($eventImage)) {
            if ($protected) {
                $url = $eventImage->getUrl($size, false, true);
            } else {
                $url = $eventImage->getWebUrl($size, false, true);
            }
        }
        return $url;
    }

    /**
     * @return string
     */
    public function getCompleteAddress()
    {
        $address = '';
        if ($this->event_address) {
            $address .= $this->event_address;
            if ($this->event_address_house_number) {
                $address .= ' ' . $this->event_address_house_number;
            }
        }
        if ($this->event_address_cap) {
            $address .= ($this->event_address ? ', ' : ' ') . $this->event_address_cap;
        }
        if (!is_null($this->cityLocation)) {
            $address .= (strlen($address) > 0 ? ' ' : '') . $this->cityLocation->nome;
        }
        return $address;
    }

    public function setPublicationScenario()
    {
        $moduleNews = \Yii::$app->getModule(AmosEvents::getModuleName());
        if ($moduleNews->hidePubblicationDate == true) {
            $this->setScenario(Event::SCENARIO_ORG_HIDE_PUBBLICATION_DATE);
        } else {
            $this->setScenario(Event::SCENARIO_ORGANIZATIONALDATA);
        }
    }

    /**
     * @param $communityId
     * @return ActiveQuery
     */
    public function getAssociationTargetQuery($communityId)
    {
        $this->community_id = $communityId;
        $userCommunityIds = $this->community->getCommunityUserMms()->select('user_profile.user_id');
        /** @var ActiveQuery $userQuery */
        $userQuery = User::find()->andFilterWhere(['not in', User::tableName() . '.id', $userCommunityIds]);
        $userQuery->joinWith('userProfile');
        $userQuery->andWhere('user_profile.id is not null');

        $userQuery->andWhere(['user_profile.attivo' => 1]);

        $userQuery->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);
        return $userQuery;
    }

    public function getEndDateHour()
    {
        $beginDateHour = $this->begin_date_hour ? $this->begin_date_hour : null;
        $lengthValue = $this->length ? $this->length : null;
        $lengthMUId = $this->length_mu_id ? $this->length_mu_id : null;
        if ($beginDateHour && $lengthValue && $lengthMUId) {
            $dbDateTimeFormat = 'Y-m-d H:i:s';
            $dateTime = \DateTime::createFromFormat($dbDateTimeFormat, $beginDateHour);
            /** @var EventLengthMeasurementUnit $eventLengthMeasurementUnitModel */
            $eventLengthMeasurementUnitModel = $this->eventsModule->createModel('EventLengthMeasurementUnit');
            $eventLengthMU = $eventLengthMeasurementUnitModel::findOne($lengthMUId);
            if (!is_null($dateTime) && !is_null($eventLengthMU) && is_numeric($lengthValue)) {
                $interval = 'P';
                $timePeriod = ['H', 'M', 'S'];
                if (in_array($eventLengthMU->date_interval_period, $timePeriod)) {
                    $interval .= 'T';
                }
                $interval .= $lengthValue . $eventLengthMU->date_interval_period;
                $dateTime->add(new \DateInterval($interval));
                $retValDateTime = $dateTime->format($dbDateTimeFormat);
                return $retValDateTime;
            }
        }
        return null;
    }

    public function getGoogleEventId()
    {
        return $this->isNewRecord ? null : ('events' . $this->id);
    }

    public function getGoogleEvent($eventCalendar = null)
    {
        $timeZone = \Yii::$app->timeZone;
        if (is_null($eventCalendar)) {
            $eventCalendar = new \Google_Service_Calendar_Event();
            $eventCalendarCreator = new \Google_Service_Calendar_EventCreator();
            $eventCalendarCreator->setDisplayName($this->createdUserProfile->getNomeCognome());
            $eventCalendar->setCreator($eventCalendarCreator);
        }
        $eventCalendar->setColorId('10');
        $eventCalendar->setSummary($this->getTitle());
        $eventCalendar->setDescription($this->summary);;
        $eventCalendarStart = new \Google_Service_Calendar_EventDateTime();
        $eventCalendarStart->setTimeZone($timeZone);

        $eventCalendarStart->setDateTime(str_replace(' ', 'T', $this->begin_date_hour));
        $eventCalendar->setStart($eventCalendarStart);
        $endDateHour = !is_null($this->end_date_hour) ? $this->end_date_hour : $this->getEndDateHour();
        if (!empty($endDateHour)) {
            $eventCalendarEnd = new \Google_Service_Calendar_EventDateTime();
            $eventCalendarEnd->setTimeZone($timeZone);
            $eventCalendarEnd->setDateTime(str_replace(' ', 'T', $endDateHour));
            $eventCalendar->setEnd($eventCalendarEnd);
        } else {
            $eventCalendar->setEnd($eventCalendarStart);
        }
        $eventCalendar->locked = true;
        $eventCalendar->setId($this->getGoogleEventId());
        $eventCalendar->setLocation($this->location);
        return $eventCalendar;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (is_null($this->deleted_at) && is_null($this->deleted_by) && !is_null($this->begin_date_hour)) {
            $socialAuth = \Yii::$app->getModule('socialauth');
            if (!is_null($socialAuth)) {
                $userIds = [$this->created_by];
                $eventCalendar = $this->getGoogleEvent();
                if ($this->status == $this->getValidatedStatus()) {
                    if ($this->isEnabledCwh()) {
                        $recipientsIds = $this->getRecipientsQuery()->select('user_id')->column();
                        $userIds = ArrayHelper::merge($userIds, $recipientsIds);
                    }
                }
                foreach ($userIds as $userId) {
                    $service = EventsUtility::getUserCalendarService($userId);
                    if (!is_null($service) && $service->service_id) {
                        $serviceGoogle = EventsUtility::getGoogleServiceCalendar($service);
                        if (!is_null($serviceGoogle)) {
                            $calendarId = $service->service_id;
                            $saved = EventsUtility::insertOrUpdateGoogleEvent($serviceGoogle, $calendarId,
                                $eventCalendar);
                        }
                    }
                }
            }
        }

        $moduleEvents = \Yii::$app->getModule('events');
        $patronage_luya_nav_id = null;
        if ($moduleEvents) {
            $patronage_luya_nav_id = $moduleEvents->patronage_luya_nav_id;
        }
        $eventLanding = $this->eventLanding;
        if (!is_null($eventLanding)) {
            if ($this->eventType->event_type == \open20\amos\events\models\EventType::TYPE_INFORMATIVE) {
                $eventLanding->date_sending_memo = null;
                $eventLanding->save(false);
            }
            if (!empty($patronage_luya_nav_id) && $this->eventType->patronage == true) {
                $eventLanding->luya_template_id = $patronage_luya_nav_id;
            }
            $eventLanding->createLandingPage($this);
        }

//        if ($this->event_container_id) {
//            $containerEvent = EventContainer::findOne($this->event_container_id);
//            if ($containerEvent) {
//                $minDate = Event::find()->andWhere(['event_container_id' => $this->event_container_id])->min('begin_date_hour');
//                $maxDate = Event::find()->andWhere(['event_container_id' => $this->event_container_id])->max('end_date_hour');
//
//                $containerEvent->begin_date = $minDate;
//                $containerEvent->end_date = $maxDate;
//
//                $containerEvent->save(false);
//            }
//        }
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (!is_null($this->begin_date_hour)) {
            $socialAuth = \Yii::$app->getModule('socialauth');
            if (!is_null($socialAuth)) {
                $eventId = $this->getGoogleEventId();
                $services = \open20\amos\socialauth\models\SocialAuthServices::find()->andWhere([
                    'and',
                    ['service' => 'calendar'],
                    ['not', ['service_id' => null]]
                ])->all();
                foreach ($services as $service) {
                    $serviceGoogle = EventsUtility::getGoogleServiceCalendar($service);
                    if (!is_null($serviceGoogle)) {
                        $calendarId = $service->service_id;
                        $deleted = EventsUtility::deleteGoogleEvent($serviceGoogle, $calendarId, $eventId);
                    }
                }
            }
        }
        return parent::beforeDelete();
    }

    /**
     * This method calculate the remaining seats available if the event is of type limited seats.
     * The method get all event community members not rejected including event managers.
     * @return int
     */
    public function getRemainingSeats($excludeEventManagers = false)
    {
        if (!$this->id || !$this->community_id) {
            return 0;
        }
        $remainingSeats = 0;
        $eventType = $this->eventType;
        if (!is_null($eventType) && $eventType->limited_seats) {
            $community = $this->getCommunityModel();
            $query = $community->getCommunityUserMms()->andWhere(['<>', CommunityUserMm::tableName() . '.status', CommunityUserMm::STATUS_REJECTED]);
            $query->innerJoin(User::tableName(), User::tableName() . '.id = ' . CommunityUserMm::tableName() . '.user_id');
            $query->andWhere([User::tableName() . '.deleted_at' => null]);
            $query->andWhere(['not like', User::tableName() . '.username', UserProfileUtility::DELETED_ACCOUNT_USERNAME_PREFIX]);
            if ($excludeEventManagers) {
                $query->andWhere(['<>', CommunityUserMm::tableName() . '.role', CommunityUserMm::ROLE_COMMUNITY_MANAGER]);
            }
            $notRejectedMembers = $query->count();
            $remainingSeats = (int)($this->seats_available - $notRejectedMembers);
        }
        return $remainingSeats;
    }

    /**
     * This method checks if there are available seats for this event.
     * If the event type does not include limited seats the method returns always true.
     * @return bool
     */
    public function thereAreAvailableSeats()
    {
        $eventType = $this->eventType;
        if (!is_null($eventType) && !$eventType->limited_seats) {
            return true;
        }
        if (!$this->id || !$this->community_id) {
            return false;
        }
        $remainingSeats = $this->getRemainingSeats();
        return ($remainingSeats > 0);
    }

    public function getInvitationStats()
    {
        $stats = [
            'registered' => 0,
            'registered_accepted' => 0,
            'registered_rejected' => 0,
            'imported' => 0,
            'imported_accepted' => 0,
            'imported_rejected' => 0,
            'partners' => 0,
            'total' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        $invitations = $eventInvitationModel::find()->where(['event_id' => $this->id])->all();
        foreach ($invitations as $invitation) {
            if ($invitation->type == EventInvitation::INVITATION_TYPE_REGISTERED) {
                ++$stats['registered'];
                if ($invitation->state == EventInvitation::INVITATION_STATE_ACCEPTED) {
                    ++$stats['registered_accepted'];
                    ++$stats['accepted'];
                } else if ($invitation->state == EventInvitation::INVITATION_STATE_REJECTED) {
                    ++$stats['registered_rejected'];
                    ++$stats['rejected'];
                }
            } else if ($invitation->type == EventInvitation::INVITATION_TYPE_IMPORTED) {
                ++$stats['imported'];
                if ($invitation->state == EventInvitation::INVITATION_STATE_ACCEPTED) {
                    ++$stats['imported_accepted'];
                    ++$stats['accepted'];
                } else if ($invitation->state == EventInvitation::INVITATION_STATE_REJECTED) {
                    ++$stats['imported_rejected'];
                    ++$stats['rejected'];
                }
            }
            if ($invitation->partner_of) {
                ++$stats['partners'];
            }
            ++$stats['total'];
        }
        return $stats;
    }

    /**
     * Gets invitations upon cwh preferences.
     * return array Array of users data
     */
    public function getCwhUserIdsToInvite()
    {
        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        // Gets ids of already invited users
        $invUids = $eventInvitationModel::find()
            ->select('user_id')
            ->where(['event_id' => $this->id])
            ->andWhere('user_id IS NOT NULL')
            ->column();
        // Get involved user ids
        $cwhUids = $this->getRecipientsQuery()
            ->select('user_id')
            ->column();
        // Gets ids of users not yet invited
        return array_diff($cwhUids, $invUids);
    }

    /**
     * Gets invited users data for this event, excluding invites already sent
     * return array Array of users data
     */
    public function getInvitationsData($withUserData = false)
    {
        if (!$this->id) {
            return [];
        }

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        // Gets all invited users (they will be all external ones)
        /** @var ActiveQuery $query */
        $query = $eventInvitationModel::find();
        $query->andWhere([
            'event_id' => $this->id,
            'state' => EventInvitation::INVITATION_STATE_INVITED,
            'invitation_sent_on' => null
        ]);
        $invitations = $query->all();
        $rows = [];
        foreach ($invitations as $invitation) {
            if ($withUserData) {
                $query = new Query();
                $query->select([
                    User::tableName() . '.email',
                    UserProfile::tableName() . '.nome',
                    UserProfile::tableName() . '.cognome',
                    UserProfile::tableName() . '.codice_fiscale'
                ]);
                $query->from(UserProfile::tableName());
                $query->innerJoin(User::tableName(), User::tableName() . '.id = ' . UserProfile::tableName() . '.user_id');
                $query->andWhere([User::tableName() . '.id' => $invitation->user_id]);
                $query->andWhere([User::tableName() . '.deleted_at' => null]);
                $query->andWhere([UserProfile::tableName() . '.deleted_at' => null]);
                $userData = $query->one();
                $email = (!empty($invitation->email) ? $invitation->email : $userData['email']);
                $fiscalCode = (!empty($invitation->fiscal_code) ? $invitation->fiscal_code : $userData['codice_fiscale']);
                $name = (!empty($invitation->name) ? $invitation->name : $userData['nome']);
                $surname = (!empty($invitation->surname) ? $invitation->surname : $userData['cognome']);
            } else {
                $email = $invitation->email;
                $fiscalCode = $invitation->fiscal_code;
                $name = $invitation->name;
                $surname = $invitation->surname;
            }
            $rows[] = [
                'id' => $invitation->id,
                'type' => $invitation->type,
                'code' => $invitation->code,
                'email' => $email,
                'fiscal_code' => $fiscalCode,
                'name' => $name,
                'surname' => $surname,
                'user_id' => $invitation->user_id,
            ];
        }
        return (array)$rows;
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowBaseStatusLabel()
    {
        $status = parent::getWorkflowBaseStatusLabel();
        return ((strlen($status) > 0) ? AmosEvents::t('amosevents', $status) : '-');
    }

    public function getFullAddress($separator = '')
    {
        // Address
        $location = ($this->event_location) ? $this->event_location . $separator : ''; //'-';
        $address = ($this->event_address) ? $this->event_address . ', ' : ''; //'-';
        $addressNumber = ($this->event_address_house_number) ? $this->event_address_house_number . ' ' : ''; //'-';
        $cap = ($this->event_address_cap) ? $separator . $this->event_address_cap . ' ' : ' '; //'-';
        $city = ($this->cityLocation) ? $this->cityLocation->nome . ' ' : ''; //'-';
        $province = ($this->provinceLocation) ? ' (' . $this->provinceLocation->sigla . ') ' : ''; //'-';
        $country = ($this->countryLocation) ? $separator . $this->countryLocation->nome : ' '; //'-' ;

        return $location . $address . $addressNumber . $cap . $city . $province . $country;
    }

    /**
     * @param $user_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function isUserSubscribedToEvent($user_id)
    {
        $count = CommunityUserMm::find()
            ->andWhere(['community_id' => $this->community_id])
            ->andWhere(['user_id' => $user_id])
            ->count();
        return ($count > 0);
    }

    /**
     * @param $idDomanda
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function import()
    {
        $submitImport = \Yii::$app->request->post('submit-import');
        $count = 0;
        if (!empty($submitImport)) {
            if ((isset($_FILES['import-file']['tmp_name']) && (!empty($_FILES['import-file']['tmp_name'])))) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    $inputFileName = $_FILES['import-file']['tmp_name'];
                    $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);

                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    $ret['file'] = true;
                    $i = 1;
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $Array = $rowData[0];
                        $sector = $Array[0];
                        $rowSeat = $Array[1];
                        $seat = $Array[2];
                        $automatic = $Array[3];
                        $available_for_groups = $Array[4];
                        if (!empty($sector) && !empty($rowSeat) && !empty($seat) && isset($automatic) && isset($available_for_groups)) {

                            /** @var EventSeats $eventSeatsModel */
                            $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

                            $structureSeats = $eventSeatsModel::find()
                                ->andWhere(['event_id' => $this->id])
                                ->andWhere(['sector' => $sector])
                                ->andWhere(['row' => $rowSeat])
                                ->andWhere(['seat' => $seat])->one();
                            if (empty($structureSeats)) {
                                /** @var EventSeats $structureSeats */
                                $structureSeats = $this->eventsModule->createModel('EventSeats');
                            }
                            $structureSeats->event_id = $this->id;
                            $structureSeats->sector = $sector;
                            $structureSeats->row = $rowSeat;
                            $structureSeats->seat = $seat;
                            $structureSeats->automatic = $automatic;
                            $structureSeats->available_for_groups = $available_for_groups;
                            $ok = $structureSeats->save();
                            if ($structureSeats->getErrors()) {
                                $errorMessage = implode("<br>", $structureSeats->getErrorSummary());
                                throw new Exception($errorMessage . "<br>" . AmosEvents::t('amosevents',
                                        "Errore in riga {n}",
                                        [
                                            'n' => $row
                                        ])
                                );
                            }
                            if ($ok) {
                                $count++;
                                $i++;
                            }
                        } else {
                            throw new Exception(AmosEvents::t('amosevents',
                                "E' neccessario compilare tutti i dati del tracciato, errore in riga {n}",
                                [
                                    'n' => $row
                                ])
                            );
                        }
                    }

                    $transaction->commit();
                    \Yii::$app->session->addFlash('success',
                        AmosEvents::t('amosevents', "Sono stati inseriti {n} posti.", ['n' => $count]));
                    return true;
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    \Yii::$app->session->addFlash('danger', $e->getMessage());
                    return false;
                }
            }
        }
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getSectorsAvailableForGroups()
    {
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');
        $sectors = $eventSeatsModel::find()
            ->andWhere(['event_id' => $this->id])
            ->andWhere(['available_for_groups' => true])
            ->andWhere(['status' => EventSeats::STATUS_EMPTY])
            ->groupBy('sector')->all();
        return $sectors;
    }

    /**
     * @param $sector
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getSeatsAvailableForGroups($sector = null)
    {
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');
        $seats = $eventSeatsModel::find()
            ->andWhere(['event_id' => $this->id])
            ->andWhere(['available_for_groups' => true])
            ->andFilterWhere(['sector' => $sector])
            ->andWhere(['status' => EventSeats::STATUS_EMPTY])
            ->all();

        return $seats;
    }

    /**
     * @param $n
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function canSubscribeGroup($n)
    {
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');
        $count = $eventSeatsModel::find()
            ->andWhere(['event_id' => $this->id])
            ->andWhere(['available_for_groups' => true])
            ->andWhere(['status' => EventSeats::STATUS_EMPTY])->count();
        return $n <= $count;
    }

    /**
     * @param $n
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function canSubscribeAutomatic()
    {
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');
        $count = $eventSeatsModel::find()
            ->andWhere(['event_id' => $this->id])
            ->andWhere(['automatic' => true])
            ->andWhere(['status' => EventSeats::STATUS_EMPTY])->count();
        return $count > 0;
    }

    /**
     * @param $user_id
     * @return EventSeats
     */
    public function assignAutomaticSeats($user_id)
    {
        /** @var  $seat EventSeats */
        $seat = $this->getEventSeats()
            ->andWhere(['status' => EventSeats::STATUS_EMPTY])
            ->andWhere(['automatic' => true])->one();

        if ($seat) {
            $seat->user_id = $user_id;
            $seat->type_of_assigned_participant = 1;
            $seat->status = EventSeats::STATUS_ASSIGNED;
            $seat->save(false);
        }
        return $seat;
    }

    /**
     *
     * @param integer $eid
     * @return integer
     */
    public function checkParticipantsQuantity()
    {
        $count = 0;

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

        $participants = $eventInvitationModel::find()
            ->andWhere(['event_id' => $this->id, 'state' => EventInvitation::INVITATION_STATE_ACCEPTED])
            ->andWhere(['deleted_at' => null, 'deleted_by' => null]);

        $count += $participants->count();

        $companions = $eventParticipantCompanionModel::find()
            ->andWhere(['event_invitation_id' => $participants->select('id')->all()])
            ->count();

        $count += $companions;


        return $count;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getSectors($empty = true)
    {

        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

        /** @var ActiveQuery $query */
        $query = $eventSeatsModel::find()
            ->andWhere(['event_id' => $this->id])
            ->groupBy('sector');
        if ($empty) {
            $query->andWhere(['status' => [EventSeats::STATUS_EMPTY, EventSeats::STATUS_TO_REASSIGN]]);
        }
        return $query->all();
    }

    /**
     * @return bool
     */
    public function isSubscribtionsOpened()
    {
        $module = \Yii::$app->getModule('events');
        $restrictionRegisterDate = true;
        if ($module) {
            $restrictionRegisterDate = $module->restrictionRegisterDate;
        }
        $current = new \DateTime('now', new \DateTimeZone('Europe/Rome'));
        $dateBeginReg = $this->registration_date_begin ? new \DateTime($this->registration_date_begin, new \DateTimeZone('Europe/Rome')) : null;
        $dateBegin = $this->begin_date_hour ? new \DateTime($this->begin_date_hour, new \DateTimeZone('Europe/Rome')) : null;
        $dateEnd = $this->end_date_hour ? new \DateTime($this->end_date_hour, new \DateTimeZone('Europe/Rome')) : null;
        $dateEndReg = $this->registration_date_end ? new \DateTime($this->registration_date_end, new \DateTimeZone('Europe/Rome')) : null;


        if (($dateBeginReg == null || $current >= $dateBeginReg) &&
            (
            !empty($dateEndReg) ? ($current <= $dateEndReg) : ($restrictionRegisterDate ? $current <= $dateBegin : true)
            )
        ) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getFullLocationString()
    {
        $position = ($this->event_address_house_number ? $this->event_address_house_number . ' ' : '');
        $position .= ($this->event_address ? $this->event_address . ', ' : '');
        $position .= (!is_null($this->cityLocation) ? $this->cityLocation->nome . ', ' : '');
        $position .= (!is_null($this->countryLocation) ? $this->countryLocation->nome : '');
        return $position;
    }

    /**
     *
     * @param integer $user_id
     * @param string $event_string
     * @return string
     */
    public function getLinkWithToken($user_id, $event_string)
    {
        $link = null;
        $tokengroup = TokenGroup::getTokenGroup($event_string);

        if ($tokengroup) {

            $tokenUser = $tokengroup->generateSingleTokenUser($user_id);
            if (!empty($tokenUser)) {
                $link = $tokenUser->getBackendTokenLink();
            }
        }
        return $link;
    }


    /**
     *
     */
    public function savePreferencesTags()
    {
        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_PREFERENCE_CENTER])->one();
        if ($root) {
            EntitysTagsMm::deleteAll(['root_id' => $root->id, 'record_id' => $this->id, 'classname' => Event::className()]);
            foreach ($this->preferencesTags as $tagId) {
                $tagsMm = new EntitysTagsMm();
                $tagsMm->tag_id = $tagId;
                $tagsMm->record_id = $this->id;
                $tagsMm->root_id = $root->id;
                $tagsMm->classname = Event::className();
                $tagsMm->save(false);
            }


            $moduleCwh = \Yii::$app->getModule('cwh');
            if ($moduleCwh) {
                $cwhConfigContents = \open20\amos\cwh\models\CwhConfigContents::findOne(['tablename' => $this->tableName()]);
                $Pubblicazione = \open20\amos\cwh\models\CwhPubblicazioni::findOne([
                    'content_id' => $this->id,
                    'cwh_config_contents_id' => $cwhConfigContents->id
                ]);

                if (!empty($this->preferencesTags) && count($this->preferencesTags) > 0) {
                    $Pubblicazione->cwh_regole_pubblicazione_id = \open20\amos\cwh\models\CwhRegolePubblicazione::ALL_USERS_WITH_TAGS;
                } else {
                    $Pubblicazione->cwh_regole_pubblicazione_id = \open20\amos\cwh\models\CwhRegolePubblicazione::ALL_USERS;
                }
                $Pubblicazione->save(false);
            }
        }
    }

    /**
     *
     */
    public function saveCustomTags()
    {
        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_CUSTOM_EVENTS])->one();
        if ($root) {
            EntitysTagsMm::deleteAll(['root_id' => $root->id, 'record_id' => $this->id, 'classname' => Event::className()]);
            $exploded = explode(',', $this->customTags);
            foreach ($exploded as $tagString) {
                $tag = Tag::find()->andWhere(['nome' => $tagString, 'root' => $root->id])->one();
                if (empty($tag)) {
                    $tag = new Tag();
                    $tag->nome = $tagString;
                    $tag->appendTo($root);
                    $ok = $tag->save(false);
                }
                if (!empty($tag->id)) {
                    $tagsMm = new EntitysTagsMm();
                    $tagsMm->tag_id = $tag->id;
                    $tagsMm->record_id = $this->id;
                    $tagsMm->root_id = $root->id;
                    $tagsMm->classname = Event::className();
                    $tagsMm->save(false);
                }
            }
            $explodedDefault = $this->customTagsDefault;
            foreach ($explodedDefault as $tagId) {
                $defaultTag = Tag::findOne($tagId);
                if ($defaultTag) {
                    $tagsMm = new EntitysTagsMm();
                    $tagsMm->tag_id = $defaultTag->id;
                    $tagsMm->record_id = $this->id;
                    $tagsMm->root_id = $root->id;
                    $tagsMm->classname = Event::className();
                    $tagsMm->save(false);
                }

            }

        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function loadCustomTags()
    {
        $this->customTags = [];
        $this->customTagsDefault = [];

        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_CUSTOM_EVENTS])->one();
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => Event::className()])
                ->andWhere(['record_id' => $this->id])
                ->andWhere(['root_id' => $root->id])
                ->andWhere(['IS', 'codice', null])
                ->all();
            foreach ($tagsMm as $tagMm) {
                $this->customTags [] = $tagMm->tag->nome;
            }
            $this->customTags = implode(',', $this->customTags);


            $tagsMmDefault = EntitysTagsMm::find()
                ->innerJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['classname' => Event::className()])
                ->andWhere(['record_id' => $this->id])
                ->andWhere(['root_id' => $root->id])
                ->andWhere(['like', 'codice', 'custom_tags_default'])
                ->all();
            foreach ($tagsMmDefault as $tagMm) {
                $this->customTagsDefault [] = $tagMm->tag;
            }
        }
    }


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function loadTagPreferences()
    {
        $root = Tag::find()->andWhere(['codice' => self::ROOT_TAG_PREFERENCE_CENTER])->one();
        if ($root) {
            $tagsMm = EntitysTagsMm::find()
                ->andWhere(['root_id' => $root->id])
                ->andWhere(['record_id' => $this->id])
                ->all();
            foreach ($tagsMm as $tagMm) {
                $this->preferencesTags [] = $tagMm->tag_id;
            }
        }
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventsTypeWithLimitedSeats()
    {
        return EventType::find()->andWhere(['limited_seats' => 1])->all();
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventsTypeWithInformativeType()
    {
        return EventType::find()->andWhere(['event_type' => EventType::TYPE_INFORMATIVE])->all();
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventsTypeWithPrivate()
    {
        return EventType::find()->andWhere(['event_type' => EventType::TYPE_UPON_INVITATION])->all();
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventsTypeWithPatronage()
    {
        return EventType::find()->andWhere(['patronage' => true])->all();
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventsTypeForStreaming()
    {
        return EventType::find()
            ->andWhere(['OR',
                ['event_type' => EventType::TYPE_OPEN, 'limited_seats' => false],
                ['event_type' => EventType::TYPE_INFORMATIVE]
            ])
            ->all();
    }

    /**
     * @return array
     */
    public function getDuplicateContentAttachmentsAttributes()
    {
        return [
            'eventLogo' => DuplicateContentUtility::ATTACHMENT_SINGLE,
            'eventAttachments' => DuplicateContentUtility::ATTACHMENT_MULTI,
            'landingHeader' => DuplicateContentUtility::ATTACHMENT_SINGLE
        ];
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function createDefaultAccreditationList()
    {
        if (empty($this->eventAccreditationLists)) {
            $accreditamentoList = $this->eventsModule->createModel('EventAccreditationList');
            $accreditamentoList->event_id = $this->id;
            $accreditamentoList->position = 1;
            $accreditamentoList->title = "Generica";
            $accreditamentoList->save();
        }
    }

    /**
     * @param bool $count
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getAllInvitedUsers($count = false)
    {
        $query = EventInvitationSent::find()
            ->innerJoin('event_internal_list', 'event_internal_list.id = event_invitation_sent.event_internal_list_id')
            ->andWhere(['event_internal_list.event_id' => $this->id])
            ->andWhere(['event_invitation_sent.template' => 'registration_email'])
            ->groupBy('user_id');
        if ($count) {
            return $query->count();
        }
        return $query->all();
    }

    /**
     * @param bool $count
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getTicketsSent($count = false)
    {
        $query = EventInvitation::find()->andWhere(['event_id' => $this->id])
            ->andWhere(['is_ticket_sent' => true]);
        if ($count) {
            return $query->count();
        }
        return $query->all();
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getTicketsDownloaded()
    {
        $query = EventInvitation::find()->andWhere(['event_id' => $this->id])
            ->andWhere(['is not', 'ticket_downloaded_at', null]);

        return $query->count();

    }

    /**
     * @return mixed
     */
    public function getRecipientsQuery()
    {
        $tagValues = null;
        $cwhActiveQuery = new CwhActiveQuery($this->className());
        $tagModule = \Yii::$app->getModule('tag');
        if (!empty($tagModule)) {
            $tagValues = $this->tagValues;
        }
        $queryUsers = $cwhActiveQuery->getRecipients($this->regola_pubblicazione, $tagValues, $this->destinatari);
        $query = UserProfile::find()->andWhere([
            'in',
            'user_id',
            $queryUsers->select('user.id')->asArray()->column()
        ]);
        return $query;
    }

    /**
     * @return string
     */
    public function getCompleteLocationString()
    {
        $location = '';
        if (!empty($this->eventLocation)) {
            $location .= $this->eventLocation->name . ' ';
        }
        if (!empty($this->eventLocationEntrances)) {
            $location .= $this->eventLocationEntrances->name;
        }
        return $location;
    }

    /**
     * @return int|string
     */
    public function getTotParticipantsPlusCompanions()
    {
        $participants = $this->getEventInvitations()->count();
        $companions = $this->getEventParticipantCompanions()->count();
        return $companions + $participants;
    }

    /**
     * @return int|string
     */
    public function getTotConfirmedParticipants()
    {
        return $this->getEventInvitations()
            ->innerJoin('community_user_mm', 'community_user_mm.user_id = event_invitation.user_id')
            ->andWhere(['community_user_mm.status' => \open20\amos\community\models\CommunityUserMm::STATUS_ACTIVE])
            ->andWhere(['community_user_mm.community_id' => $this->community_id])
            ->groupBy('user_id')
            ->count();
    }

    /**
     * @return int|string
     */
    public function getTotWaitingParticipants()
    {

        return $this->getEventInvitations()
            ->innerJoin('community_user_mm', 'community_user_mm.user_id = event_invitation.user_id')
            ->andWhere(['community_user_mm.status' => \open20\amos\community\models\CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER])
            ->andWhere(['community_user_mm.community_id' => $this->community_id])
            ->groupBy('user_id')
            ->count();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTagCustomDefault()
    {
        $root = \open20\amos\tag\models\Tag::find()->andWhere(['codice' => self::ROOT_TAG_CUSTOM_EVENTS])->one();
        return Tag::find()
            ->andWhere(['root' => $root->id])
            ->andWhere(['like', 'codice', 'custom_tags_default'])->all();
    }

    /**
     * @return bool
     */
    public function isSeatManagement()
    {
        if ($this->seats_management && $this->eventType->limited_seats) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getMainImageEvent()
    {
        $url = "/img/img_default.jpg";
        if ($this->eventType->patronage) {
            $moduleEvents = \Yii::$app->getModule('events');
            if ($moduleEvents) {
                if (!empty($moduleEvents->default_image_patronage)) {
                    $url = \Yii::$app->params['platform']['backendUrl'] . $moduleEvents->default_image_patronage;
                }
            }
        } else if (!empty($this->eventLogo)) {
            /** @var  $a File */
            $url = $this->eventLogo->getWebUrl('original', false, true);
        }
        return $url;
    }

    /**
     * @return bool
     */
    public function sendNotification()
    {
        $module = \Yii::$app->getModule('events');
        if ($module) {
            if ($module->enableNewWizard) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isEventPublic()
    {
        $isStatusPublished = (Event::EVENTS_WORKFLOW_STATUS_PUBLISHED == $this->status);
        $pubblDateEnd = null;
        $pubblDateBegin = null;
        $now = new \DateTime();
        $this->publication_date_begin;
        if (!empty($this->publication_date_begin)) {
            $pubblDateBegin = new \DateTime($this->publication_date_begin);
        }
        if (!empty($this->publication_date_end)) {
            $pubblDateEnd = new \DateTime($this->publication_date_end);
        }

        if ($pubblDateBegin && $isStatusPublished && $now > $pubblDateBegin) {
            if (!empty($pubblDateEnd)) {
                if ($now < $pubblDateEnd) {
                    return true;
                }
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getPublishErrors()
    {
        $location = $this->eventLocation;
        if ($location && $location->is_location_streaming) {
            $landing = $this->eventLanding;
            if ($landing && empty($landing->streaming_url)) {
                $errors [] = AmosEvents::t('amosevents', "Prima di pubblicare l'evento devi settare i dettagli dello streaming nella pagina") . " "
                    . \yii\helpers\Html::a("Gestione contenuti", ["/events/event-dashboard/landing-manage-contents", 'id' => $this->id]);
            }

        }
        return $errors;
    }

    /**
     * @return string
     */
    public function getContentAbsoluteUrl()
    {
        return EventsUtility::getUrlLanding($this);
    }

    /**
     * @param $params
     * @return EventSearch
     */
    public function getQueryFeedRss($params)
    {
        $modelSearch = new EventSearch();
        $query = $modelSearch->getRssFeedQuery($params);
        return $query;
    }

}