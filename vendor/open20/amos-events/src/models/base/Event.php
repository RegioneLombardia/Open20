<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models\base
 * @category   CategoryName
 */

namespace open20\amos\events\models\base;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\community\models\CommunityInterface;
use open20\amos\events\AmosEvents;
use open20\amos\events\validators\CapValidator;
use open20\amos\core\record\ContentModel;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class Event
 * This is the base-model class for table "event".
 *
 * @property integer $id
 * @property string $status
 * @property string $title
 * @property string $summary
 * @property string $description
 * @property integer $event_group_referent_id
 * @property string $begin_date_hour
 * @property integer $length
 * @property string $end_date_hour
 * @property string $publication_date_begin
 * @property string $publication_date_end
 * @property string $registration_date_begin
 * @property string $registration_date_end
 * @property string $show_community
 * @property string $show_on_frontend
 * @property string $landing_url
 * @property string $frontend_page_title
 * @property string $frontend_claim
 * @property string $registration_limit_date
 * @property string $event_location
 * @property string $event_address
 * @property string $event_address_house_number
 * @property string $event_address_cap
 * @property string $gdpr_question_1
 * @property string $gdpr_question_2
 * @property string $gdpr_question_3
 * @property string $gdpr_question_4
 * @property string $gdpr_question_5
 * @property integer $seats_available
 * @property integer $paid_event
 * @property integer $publish_in_the_calendar
 * @property integer $visible_in_the_calendar
 * @property integer $event_commentable
 * @property integer $event_management
 * @property integer $validated_at_least_once
 * @property integer $city_location_id
 * @property integer $province_location_id
 * @property integer $country_location_id
 * @property integer $event_membership_type_id
 * @property integer $length_mu_id
 * @property integer $event_type_id
 * @property integer $community_id
 * @property integer $event_container_id
 * @property integer $publish_on_prl
 * @property integer $event_id
 * @property integer $advanced_options_event
 * @property integer $advanced_options_community
 * @property integer $seats_management
 * @property integer $has_tickets
 * @property integer $slots_calendar_management
 * @property integer $has_qr_code
 * @property integer $abilita_codice_fiscale_in_form
 * @property integer $numero_max_accompagnatori
 * @property string $thank_you_page_view
 * @property string $subscribe_form_page_view
 * @property string $event_closed_page_view
 * @property string $event_full_page_view
 * @property string $ticket_layout_view
 * @property string $email_view
 * @property string $email_subscribe_view
 * @property string $sent_credential
 * @property string $email_credential_view
 * @property integer $publish_to_prl
 * @property integer $manage_waiting_list
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $primo_piano
 * @property integer $in_evidenza
 * @property integer $use_default_custom_tags
 * @property integer $is_father
 *
 * @property \open20\amos\events\models\EventType $eventType
 * @property \open20\amos\admin\models\UserProfile $users
 * @property \open20\amos\comuni\models\IstatComuni $cityLocation
 * @property \open20\amos\comuni\models\IstatProvince $provinceLocation
 * @property \open20\amos\comuni\models\IstatNazioni $countryLocation
 * @property \open20\amos\events\models\EventMembershipType $eventMembershipType
 * @property \open20\amos\events\models\EventLengthMeasurementUnit $eventLengthMeasurementUnit
 * @property \open20\amos\community\models\CommunityUserMm $communityUserMm
 * @property \open20\amos\community\models\Community $community
 *
 * @package open20\amos\events\models\base
 */
abstract class Event extends ContentModel implements CommunityInterface
{
    const EVENTS_WORKFLOW = 'EventWorkflow';
    const EVENTS_WORKFLOW_STATUS_DRAFT = 'EventWorkflow/DRAFT';
    const EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST = 'EventWorkflow/PUBLISHREQUEST';
    const EVENTS_WORKFLOW_STATUS_PUBLISHED = 'EventWorkflow/PUBLISHED';

    const BOOLEAN_FIELDS_VALUE_YES = 1;
    const BOOLEAN_FIELDS_VALUE_NO = 0;

    /**
     * Used for create events in the traditional form (action create).
     */
    const SCENARIO_CREATE = 'scenario_create';

    /**
     * All the scenarios listed below are for the wizard.
     */
    const SCENARIO_INTRODUCTION = 'scenario_introduction';
    const SCENARIO_DESCRIPTION = 'scenario_description';
    const SCENARIO_ORGANIZATIONALDATA = 'scenario_organizationaldata';
    const SCENARIO_PUBLICATION = 'scenario_publication';
    const SCENARIO_SUMMARY = 'scenario_summary';

    const SCENARIO_ORG_HIDE_PUBBLICATION_DATE = 'scenario_org_hide_pubblication_date';
    const SCENARIO_CREATE_HIDE_PUBBLICATION_DATE = 'scenario_create_hide_pubblication_date';

    const SCENARIO_WIZARD_BASEINFO = 'scenario_wizard_baseinfo';
    const SCENARIO_WIZARD_WHERE_AND_WHEN = 'scenario_wizard_where_and_when';
    const SCENARIO_WIZARD_IMAGE = 'scenario_wizard_image';
    const SCENARIO_WIZARD_EMAILS = 'scenario_wizard_emails';
    const SCENARIO_WIZARD_LANDING = 'scenario_wizard_landing';
    const SCENARIO_WIZARD_COMMUNITY = 'scenario_wizard_community';
    const SCENARIO_WIZARD_INVITE = 'scenario_wizard_invite';


    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->eventsModule = AmosEvents::instance();

        parent::init();

        if ($this->isNewRecord) {
            if (!is_null($this->eventsModule)) {
                if ($this->eventsModule->hidePubblicationDate) {
                    // the news will be visible forever
                    $this->publication_date_end = '9999-12-31';
                }
                $this->publication_date_begin = date('Y-m-d');
            }
            $this->event_membership_type_id = \open20\amos\events\models\EventMembershipType::TYPE_OPEN;
            $this->status = $this->getWorkflowSource()->getWorkflow(self::EVENTS_WORKFLOW)->getInitialStatusId();

            if ($this->status == self::EVENTS_WORKFLOW_STATUS_PUBLISHED) {
                $this->validated_at_least_once = Event::BOOLEAN_FIELDS_VALUE_YES;
                $this->visible_in_the_calendar = Event::BOOLEAN_FIELDS_VALUE_YES;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $requiredFields = $this->eventsModule->eventsRequiredFields;
        if ($this->eventsModule->eventLengthRequired) {
            $requiredFields = ArrayHelper::merge($requiredFields, ['length']);
        }

        if ($this->eventsModule->eventMURequired) {
            $requiredFields = ArrayHelper::merge($requiredFields, ['length_mu_id']);
        }

        $rules = ArrayHelper::merge(
            parent::rules(), [
            [$requiredFields, 'required'],
            ['event_location_id', 'required', 'on' => self::SCENARIO_WIZARD_WHERE_AND_WHEN],
            [['show_community', 'show_on_frontend', 'has_tickets', 'has_qr_code', 'abilita_codice_fiscale_in_form', 'event_management'], 'default', 'value' => 0],
            [[
                'event_group_referent_id',
                'show_community',
                'begin_date_hour',
                'end_date_hour',
                'length_mu_id',
                'event_location',
                'event_address',
                'event_address_house_number',
                'event_address_cap',
                'registration_limit_date',
                'event_membership_type_id',
                'city_location_id',
                'province_location_id',
                'country_location_id',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
                'seats_available',
                'tagValues',
                'numero_max_accompagnatori',
                'gdpr_question_1',
                'gdpr_question_2',
                'gdpr_question_3',
                'gdpr_question_4',
                'gdpr_question_5',
                'thank_you_page_view',
                'subscribe_form_page_view',
                'email_view',
                'event_closed_page_view',
                'event_full_page_view',
                'ticket_layout_view',
                'email_subscribe_view',
                'email_credential_view',
                'registration_date_begin',
                'registration_date_end',
                'seats_management',
                'beginDate',
                'beginHour',
                'endDate',
                'endHour',
                'publication_date_end',
                'event_location_id',
                'event_location_entrance_id',
                'event_category_id',
                'preferencesTags',
                'customTags',
                'customTagsDefault',
                'limitedSeats',
                'video_streaming',
                'conference_call',
                'dial_code',
                'publish_on_prl',
                'event_id',
                'manage_waiting_list',
                'advanced_options_event',
                'advanced_options_community',
                'enter_time'
            ], 'safe'],
            [[
                'seats_available',
                'primo_piano',
                'in_evidenza',
                'city_location_id',
                'province_location_id',
                'country_location_id',
                'event_membership_type_id',
                'event_type_id',
                'community_id',
                'abilita_codice_fiscale_in_form',
                'numero_max_accompagnatori',
                'has_tickets',
                'has_qr_code',
                'created_by',
                'updated_by',
                'deleted_by',
                'numero_max_accompagnatori',
                'slots_calendar_management',
                'sent_credential',
                'use_token',
                'use_default_custom_tags',
                'is_father',
            ], 'integer'],
            ['eventLogoMobile', 'file'],
            [['length'], 'number', 'min' => 1, 'integerOnly' => true],
            [['title', 'event_address'], 'string', 'max' => 100],
            [['status', 'event_location', 'email_credential_subject', 'email_invitation_custom', 'thank_you_page_already_registered_view', 'token_group_string_code'], 'string', 'max' => 255],
            [['description', 'email_ticket_layout_custom', 'email_ticket_sender', 'email_ticket_subject'], 'string'],
            [['event_address_cap'], CapValidator::className()],
            [['event_address_cap'], 'string', 'max' => 5],
            [['event_location', 'event_address', 'event_address_cap', 'event_address_house_number', 'country_location_id'], 'required', 'when' => function ($model) {
                /** @var \open20\amos\events\models\Event $model */
                if (is_null($this->eventType)) {
                    return false;
                }
                return ($this->eventType->locationRequested == 1 ? true : false);
            }, 'whenClient' => "function (attribute, value) {
                return " . (!is_null($this->eventType) ? $this->eventType->locationRequested : 0) . ";
            }"],
            [['province_location_id', 'city_location_id'], 'required', 'when' => function ($model) {
                /** @var \open20\amos\events\models\Event $model */
                if (is_null($this->eventType)) {
                    return false;
                }
                return ((($this->eventType->locationRequested == 1) && ($this->country_location_id == 1)) ? true : false);
            }, 'whenClient' => "function (attribute, value) {
                return " . (!is_null($this->eventType) ? ((($this->eventType->locationRequested == 1) && ($this->country_location_id == 1)) ? 1 : 0) : 0) . ";
            }"],
            [['length', 'length_mu_id'], 'required', 'when' => function ($model) {
                /** @var \open20\amos\events\models\Event $model */
                if (is_null($this->eventType)) {
                    return false;
                }
                return ($model->eventType->durationRequested == 1 ? true : false);
            }, 'whenClient' => "function (attribute, value) {
                return " . (!is_null($this->eventType) ? $this->eventType->durationRequested : 0) . ";
            }"],
            [['event_membership_type_id', 'seats_available', 'paid_event'], 'required', 'when' => function ($model) {
                /** @var \open20\amos\events\models\Event $model */
                return ($model->event_management == 1 ? true : false);
            }, 'whenClient' => "function (attribute, value) {
                return ($('#event-event_management').val() == '1');
            }"],
            [['seats_available'], 'required', 'when' => function ($model) {
                /** @var \open20\amos\events\models\Event $model */
                return (!is_null($this->eventType) ? $this->eventType->limited_seats == 1 ? true : false : false);
            }, 'whenClient' => "function (attribute, value) {
                return " . (!is_null($this->eventType) ? $this->eventType->limited_seats == 1 ? 1 : 0 : 0) . ";
            }"],

            ['beginDate', 'checkDateWizard'],
            ['registration_date_begin', 'checkDateReg'],
            ['publication_date_begin', 'checkDatePubl'],

        ]);

        if ($this->scenario != self::SCENARIO_ORG_HIDE_PUBBLICATION_DATE && $this->scenario != self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE && $this->scenario
            && (!empty($this->publication_date_begin) && !empty($this->publication_date_end))) {
            $rules = ArrayHelper::merge($rules, [
                ['publication_date_begin', 'checkDate'],
            ]);

            if (!$this->eventsModule->enableNewWizard) {
                $rules = ArrayHelper::merge($rules, [
                    ['publication_date_begin', 'compare', 'compareAttribute' => 'publication_date_end', 'operator' => '<='],
                    ['publication_date_end', 'compare', 'compareAttribute' => 'publication_date_begin', 'operator' => '>='],
                ]);
            }
        }

        return $rules;
    }

    /**
     * Validation of $attribute if the attribute publication date of the module is true
     * @param string $attribute
     * @param array $params
     */
    public function checkDate($attribute, $params)
    {
        $isValid = true;
        if ($this->isNewRecord && \Yii::$app->getModule('events')->validatePublicationDateEnd == true) {
            if ($this->$attribute < date('Y-m-d')) {
                $isValid = false;
            }
        }

        if (!$isValid) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . ' ' . AmosEvents::t('amosevents', "may not be less than today's date"));
        }
    }

    /**
     * Validation of $attribute if the attribute publication date of the module is true
     * @param string $attribute
     * @param array $params
     */
    public function checkDateWizard($attribute, $params)
    {
        $beginDate = new \DateTime($this->begin_date_hour);
        $endDate = new \DateTime($this->end_date_hour);
        if ($beginDate > $endDate) {
            $this->addError('beginDate', AmosEvents::t('amosevents', "La Data di inizio deve essere minore della data di fine"));
            $this->addError('endDate', AmosEvents::t('amosevents', "La Data di inizio deve essere minore della data di fine"));
        }
    }


    /**
     * Validation of $attribute if the attribute publication date of the module is true
     * @param string $attribute
     * @param array $params
     */
    public function checkDateReg($attribute, $params)
    {
        $beginDate = new \DateTime($this->registration_date_begin);
        $endDate = new \DateTime($this->registration_date_end);
        if (!empty($this->registration_date_end)) {
            if ($beginDate > $endDate) {
                $this->addError('registration_date_begin', AmosEvents::t('amosevents', "La Data di inizio deve essere minore della data di fine"));
                $this->addError('registration_date_end', AmosEvents::t('amosevents', "La Data di inizio deve essere minore della data di fine"));
            }
        }
    }

    /**
     * Validation of $attribute if the attribute publication date of the module is true
     * @param string $attribute
     * @param array $params
     */
    public function checkDatePubl($attribute, $params)
    {
        $beginDate = new \DateTime($this->publication_date_begin);
        $endDate = new \DateTime($this->publication_date_end);
        if (!empty($this->publication_date_end)) {
            if ($beginDate > $endDate) {
                $this->addError('publication_date_begin', AmosEvents::t('amosevents', "La Data di inizio deve essere minore della data di fine"));
                $this->addError('publication_date_end', AmosEvents::t('amosevents', "La Data di inizio deve essere minore della data di fine"));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public
    function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::EVENTS_WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'workflowLog' => [
                    'class' => WorkflowLogFunctionsBehavior::className()
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public
    function scenarios()
    {
        $scenarios = ArrayHelper::merge(
            parent::scenarios(),
            $this->createActionScenarios()
        );

        /** @var AmosEvents $eventModule */
        $eventModule = Yii::$app->getModule(AmosEvents::getModuleName());
        if ($eventModule->params['site_publish_enabled']) {
            $scenarios[self::SCENARIO_CREATE][] = 'primo_piano';
        }

        if ($eventModule->params['site_featured_enabled']) {
            $scenarios[self::SCENARIO_CREATE][] = 'in_evidenza';
        }

        $scenarios[self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE] = $scenarios[self::SCENARIO_CREATE];


        $scenarios[self::SCENARIO_WIZARD_BASEINFO] = [
            'event_type_id',
            'title',
            'description',
            'summary',
            'event_category_id',
            'preferencesTags',
            'customTags',
            'limitedSeats',
            'video_streaming',
            'conference_call',
            'dial_code',
            'seats_available',
            'seats_maangement',
            'event_commentable',
            'abilita_codice_fiscale_in_form',
            'has_tickets',
            'has_qr_code',
            'event_group_referent_id',
            'enter_time'
        ];


        $scenarios[self::SCENARIO_WIZARD_WHERE_AND_WHEN] = [
            'begin_date_hour',
            'end_date_hour',
            'beginDate',
            'beginHour',
            'endDate',
            'endHour',
            'event_location_id',
            'event_location_entrance_id',
        ];

        $scenarios[self::SCENARIO_WIZARD_IMAGE] = [
            'titolo',
            'eventLogoMobile',
            'eventLogo',
        ];

        $scenarios[self::SCENARIO_WIZARD_LANDING] = [
            'registration_date_begin',
            'registration_date_end',
            'publication_date_end',
            'publication_date_begin',
        ];

        $scenarios[self::SCENARIO_WIZARD_COMMUNITY] = [
            'titolo',
            'show_community'
        ];

        $scenarios[self::SCENARIO_WIZARD_EMAILS] = [
            'titolo',
        ];
        return $scenarios;
    }

    /**
     * All create action behaviors.
     * @return array
     */
    private
    function createActionScenarios()
    {
        return [
            self::SCENARIO_CREATE => [
                'event_type_id',
                'title'
            ],
            self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE => [
                'event_type_id',
                'title'
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public
    function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosEvents::t('amosevents', 'ID'),
            'status' => AmosEvents::t('amosevents', 'Status'),
            'title' => AmosEvents::t('amosevents', 'Title'),
            'summary' => AmosEvents::t('amosevents', 'Sottotitolo'),
            'description' => AmosEvents::t('amosevents', 'Description'),
            'begin_date_hour' => AmosEvents::t('amosevents', 'Begin Date And Hour'),
            'length' => AmosEvents::t('amosevents', 'Length'),
            'end_date_hour' => AmosEvents::t('amosevents', 'End Date And Hour'),
            'notes' => AmosEvents::t('amosevents', '#participant_note'),
            'publication_date_begin' => AmosEvents::t('amosevents', 'Publication Date Begin'),
            'publication_date_end' => AmosEvents::t('amosevents', 'Publication Date End'),
            'publication_date_begin' => AmosEvents::t('amosevents', 'Data e ora di inizio pubblicazione'),
            'publication_date_end' => AmosEvents::t('amosevents', 'Data e ora di fine pubblicazione'),
            'event_category_id' => AmosEvents::t('amosevents', 'Tipologia'),
            'event_location_id' => AmosEvents::t('amosevents', 'Location'),
            'registration_date_begin' => AmosEvents::t('amosevents', 'Data e ora di apertura iscrizione'),
            'registration_date_end' => AmosEvents::t('amosevents', 'Data e ora chiusura iscrizioni'),
            'show_community' => AmosEvents::t('amosevents', '#show_community_label'),
            'show_on_frontend' => AmosEvents::t('amosevents', '#show_on_frontend_label'),
            'landing_url' => AmosEvents::t('amosevents', '#landing_url_label'),
            'frontend_page_title' => AmosEvents::t('amosevents', '#frontend_page_title_label'),
            'frontend_claim' => AmosEvents::t('amosevents', '#frontend_claim_label'),
            'registration_limit_date' => AmosEvents::t('amosevents', 'Registration Limit Date'),
            'event_location' => AmosEvents::t('amosevents', 'Event Location'),
            'event_address' => AmosEvents::t('amosevents', 'Event Address'),
            'event_address_house_number' => AmosEvents::t('amosevents', 'Event Address House Number'),
            'event_address_cap' => AmosEvents::t('amosevents', 'Event Address Cap'),
            'seats_available' => AmosEvents::t('amosevents', 'Seats Available'),
            'paid_event' => AmosEvents::t('amosevents', 'Paid Event'),
            'publish_in_the_calendar' => AmosEvents::t('amosevents', 'Publish In The Calendar'),
            'visible_in_the_calendar' => AmosEvents::t('amosevents', 'Visible In The Calendar'),
            'event_commentable' => AmosEvents::t('amosevents', 'Event Commentable'),
            'email_credential_view' => AmosEvents::t('amosevents', 'View custom della mail delle credenziali'),
            'event_management' => AmosEvents::t('amosevents', 'Event Management'),
            'validated_at_least_once' => AmosEvents::t('amosevents', 'Validated At Least Once'),
            'seats_management' => AmosEvents::t('amosevents', 'Gestione posti'),
            'sent_credential' => AmosEvents::t('amosevents', 'Invia le credenziali'),
            'country_location_id' => AmosEvents::t('amosevents', 'Country Location'),
            'province_location_id' => AmosEvents::t('amosevents', 'Province Location'),
            'city_location_id' => AmosEvents::t('amosevents', 'City Location'),
            'event_membership_type_id' => AmosEvents::t('amosevents', 'Event Membership Type ID'),
            'email_credential_subject' => AmosEvents::t('amosevents', 'Soggetto della mail delle credenziali'),
            'length_mu_id' => AmosEvents::t('amosevents', 'Length Measurement Unit ID'),
            'event_type_id' => AmosEvents::t('amosevents', 'Event Type'),
            'community_id' => AmosEvents::t('amosevents', 'Community ID'),
            'created_at' => AmosEvents::t('amosevents', 'Created At'),
            'updated_at' => AmosEvents::t('amosevents', 'Updated At'),
            'deleted_at' => AmosEvents::t('amosevents', 'Deleted At'),
            'created_by' => AmosEvents::t('amosevents', 'Created By'),
            'updated_by' => AmosEvents::t('amosevents', 'Updated By'),
            'deleted_by' => AmosEvents::t('amosevents', 'Deleted By'),
            'primo_piano' => AmosEvents::t('amosevents', 'Pubblica sul sito'),
            'in_evidenza' => AmosEvents::t('amosevents', 'In evidenza'),
            'eventType' => AmosEvents::t('amosevents', 'Event Type'),
            'eventLengthMeasurementUnit' => AmosEvents::t('amosevents', 'Length Measurement Unit'),
            'eventMembershipType' => AmosEvents::t('amosevents', 'Event Membership Type'),
            'subscribe_form_page_view' => AmosEvents::t('amosevents', 'Custom view form di iscrizione'),
            'thank_you_page_view' => AmosEvents::t('amosevents', 'Thank you page custom'),
            'use_token' => AmosEvents::t('amosevents', 'Usa token di accesso'),
            'token_group_string_code' => AmosEvents::t('amosevents', 'Codice del gruppo di token'),
            'thank_you_page_already_registered_view' => AmosEvents::t('amosevents', 'Thank you page custom per utenti già registrati'),
            'email_view' => AmosEvents::t('amosevents', 'email_view'),
            'email_ticket_layout_custom' => AmosEvents::t('amosevents', 'Layout della mail del ticket'),
            'email_invitation_custom' => AmosEvents::t('amosevents', 'View custom della mail di invito'),
            'email_ticket_sender' => AmosEvents::t('amosevents', 'Sender della mail del ticket'),
            'email_ticket_subject' => AmosEvents::t('amosevents', 'Soggetto della mail del ticket'),
            'event_closed_page_view' => AmosEvents::t('amosevents', 'event_closed_page_view'),
            'event_full_page_view' => AmosEvents::t('amosevents', 'event_full_page_view'),
            'ticket_layout_view' => AmosEvents::t('amosevents', 'ticket_layout_view'),
            'email_subscribe_view' => AmosEvents::t('amosevents', 'email_subscribe_view'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventType()
    {
        return $this->hasOne($this->eventsModule->model('EventType'), ['id' => 'event_type_id']);
    }

    /**
     * @inheritdoc
     */
    public
    function getCommunityId()
    {
        return $this->community_id;
    }

    /**
     * @inheritdoc
     */
    public
    function setCommunityId($communityId)
    {
        $this->community_id = $communityId;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getCommunity()
    {
        return $this->hasOne(\open20\amos\community\models\Community::className(), ['id' => 'community_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getCommunityUserMm()
    {
        return $this->hasMany(\open20\amos\community\models\CommunityUserMm::className(), ['community_id' => 'community_id']);
    }

    /**
     * @return string
     */
    public
    function getAttrEventTypeMm()
    {
        return '' . $this->eventType->title;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getCityLocation()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatComuni::className(), ['id' => 'city_location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getProvinceLocation()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatProvince::className(), ['id' => 'province_location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getCountryLocation()
    {
        return $this->hasOne(\open20\amos\comuni\models\IstatNazioni::className(), ['id' => 'country_location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventMembershipType()
    {
        return $this->hasOne($this->eventsModule->model('EventMembershipType'), ['id' => 'event_membership_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventLengthMeasurementUnit()
    {
        return $this->hasOne($this->eventsModule->model('EventLengthMeasurementUnit'), ['id' => 'length_mu_id']);
    }

    public
    function countGdprQuestions()
    {
        $count = 0;
        if ($this->eventsModule->enableGdpr) {
            if (!empty($this->gdpr_question_1)) {
                $count++;
            }
            if (!empty($this->gdpr_question_2)) {
                $count++;
            }
            if (!empty($this->gdpr_question_3)) {
                $count++;
            }
            if (!empty($this->gdpr_question_4)) {
                $count++;
            }
            if (!empty($this->gdpr_question_5)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventSeats()
    {
        return $this->hasMany($this->eventsModule->model('EventSeats'), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventCalendars()
    {
        return $this->hasMany(\open20\amos\events\models\EventCalendars::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventLocation()
    {
        return $this->hasOne(\open20\amos\events\models\EventLocation::className(), ['id' => 'event_location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventLocationEntrance()
    {
        return $this->hasOne(\open20\amos\events\models\EventLocationEntrances::className(), ['id' => 'event_location_entrance_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventInternalLists()
    {
        return $this->hasMany(\open20\amos\events\models\EventInternalList::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventLanding()
    {
        return $this->hasOne(\open20\amos\events\models\EventLanding::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventEmailTemplates()
    {
        return $this->hasOne(\open20\amos\events\models\EventEmailTemplates::className(), ['event_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventAccreditationLists()
    {
        return $this->hasMany(\open20\amos\events\models\EventAccreditationList::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventInvitations()
    {
        return $this->hasMany(\open20\amos\events\models\EventInvitation::className(), ['event_id' => 'id']);
    }

    public function getEventParticipantCompanions()
    {
        return $this->hasMany(\open20\amos\events\models\EventParticipantCompanion::className(), ['event_invitation_id' => 'id'])->via('eventInvitations');

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public
    function getEventCommunications()
    {
        return $this->hasMany(\open20\amos\events\models\EventCommunication::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventContainer()
    {
        return $this->hasOne($this->eventsModule->model('EventContainer'), ['id' => 'event_container_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventGroupReferent()
    {
        return $this->hasOne(\open20\amos\events\models\EventGroupReferent::className(), ['id' => 'event_group_referent_id']);
    }

}
