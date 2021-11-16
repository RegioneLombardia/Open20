<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\events\widgets;

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\JsUtility;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\EventSeats;
use open20\amos\events\utility\EventsUtility;
use kartik\grid\GridView;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

/**
 * Class CommunityEventMembersWidget
 * @package open20\amos\events\widgets
 */
class CommunityEventMembersWidget extends Widget
{
    /**
     * @var Community $model
     */
    public $model = null;

    /**
     * @var Event $eventModel
     */
    public $eventModel = null;

    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;
    
    /**
     * (eg. ['PARTICIPANT'] - thw widget will show only member with role participant)
     * @var array Array of roles to show
     */
    public $showRoles = null;
    
    /**
     * @var bool $showAdditionalAssociateButton Set to true if another 'invite user' button is required
     */
    public $showAdditionalAssociateButton = false;
    
    /**
     * @var array $additionalColumns Additional Columns
     */
    public $additionalColumns = [];
    
    /**
     * @var bool $viewEmail
     */
    public $viewEmail = false;
    
    /**
     * @var bool $viewInvitation
     */
    public $viewInvitation = true;
    
    /**
     * @var bool $checkManagerRole
     */
    public $checkManagerRole = false;
    
    /**
     * @var string $addPermission
     */
    public $addPermission = 'COMMUNITY_UPDATE';
    
    /**
     * @var string $manageAttributesPermission
     */
    public $manageAttributesPermission = 'COMMUNITY_UPDATE';
    
    /**
     * @var bool $forceActionColumns
     */
    public $forceActionColumns = false;
    
    /**
     * @var string $actionColumnsTemplate
     */
    public $actionColumnsTemplate = '';
    
    /**
     * @var bool $viewM2MWidgetGenericSearch
     */
    public $viewM2MWidgetGenericSearch = false;
    
    /**
     * @var array $targetUrlParams
     */
    public $targetUrlParams = null;
    
    /**
     * @var string $gridId
     */
    public $gridId = 'community-members-grid';
    
    public $enableModal = false;
    
    /**
     * @var string $delete_member_message
     */
    public $delete_member_message = false;
    
    /**
     * @var string
     */
    public $communityManagerRoleName = '';

    public $finalGridId = '';

    public $pjaxId;

    public $pageUrl;

    public $enableAdditionalButtons = false;

    public $showSearch = false;
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->eventsModule = AmosEvents::instance();

        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }
        if (($this->model instanceof Community)) {
            $this->enableModal = true;
        }
        
        $this->delete_member_message = ($this->delete_member_message) ? $this->delete_member_message : Yii::t('amoscommunity', 'Are you sure to remove this user?');
    }
    
    protected function throwErrorMessage($field)
    {
        return AmosCommunity::t('amoscommunity', 'Wrong widget configuration: missing field {field}', [
            'field' => $field
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->eventModel = $this->model;

        /** @var EventInvitation $eventInvitationModel */
        $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');
        /** @var EventAccreditationList $eventAccreditationListModel */
        $eventAccreditationListModel = $this->eventsModule->createModel('EventAccreditationList');
        /** @var EventParticipantCompanion $eventParticipantCompanionModel */
        $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');

        $customInvitationForm = AmosCommunity::instance()->customInvitationForm;
        $inviteUserOfcommunityParent = AmosCommunity::instance()->inviteUserOfcommunityParent;
        
        $gridId = $this->gridId . (!empty($this->showRoles) ? '-' . implode('-', $this->showRoles) : '');
        $this->finalGridId = $gridId;
        $model = $this->model;
        $params = [];
        $params['showRoles'] = $this->showRoles;
        $params['showAdditionalAssociateButton'] = $this->showAdditionalAssociateButton;
        $params['additionalColumns'] = $this->additionalColumns;
        $params['viewEmail'] = $this->viewEmail;
        $params['viewInvitation'] = $this->viewInvitation;
        $params['checkManagerRole'] = $this->checkManagerRole;
        $params['addPermission'] = $this->addPermission;
        $params['manageAttributesPermission'] = $this->manageAttributesPermission;
        $params['forceActionColumns'] = $this->forceActionColumns;
        $params['actionColumnsTemplate'] = $this->actionColumnsTemplate;
        $params['viewM2MWidgetGenericSearch'] = $this->viewM2MWidgetGenericSearch;
        $params['targetUrlParams'] = $this->targetUrlParams;
        $params['enableModal'] = $this->enableModal;
        $params['gridId'] = $this->gridId;
        $params['communityManagerRoleName'] = $this->communityManagerRoleName;
        
        $url = \Yii::$app->urlManager->createUrl([
            '/community/community/community-members',
            'id' => $model->id,
            'classname' => $model->className(),
            'params' => $params
        ]);
        $searchPostName = 'searchMemberName' . (!empty($this->showRoles) ? implode('', $this->showRoles) : '');
        
        $js = JsUtility::getSearchM2mFirstGridJs($gridId, $url, $searchPostName);
        PjaxAsset::register($this->getView());
        //$this->getView()->registerJs($js, View::POS_LOAD);
        
        $itemsMittente = [
            'Photo' => [
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'Photo'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'Photo'),
                ],
                'label' => AmosCommunity::t('amoscommunity', 'Photo'),
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /** @var \open20\amos\admin\models\UserProfile $userProfile */
                    $userProfile = $model->user->getProfile();
                    return UserCardWidget::widget(['model' => $userProfile]);
                }
            ],
            'name' => [
                'attribute' => 'user.userProfile.surnameName',
                'label' => AmosCommunity::t('amoscommunity', 'Name'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'name'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'name'),
                ],
                'value' => function ($model) use ($eventInvitationModel) {
                    if($this->eventModel->has_tickets) {
                        $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                        if(empty($invitation['name']) && empty($invitation['surname'])) {
                            $userProfile = UserProfile::findOne(['user_id' => $model->user_id]);
                            if($userProfile) {
                                $invitation['name'] = $userProfile['nome'];
                                $invitation['surname'] = $userProfile['cognome'];
                            }
                        }
                        return $invitation['surname'] . ' ' . $invitation['name'];
                    } else {
                        /** @var \open20\amos\community\models\CommunityUserMm $model */
                        return Html::a($model->user->userProfile->surnameName, ['/admin/user-profile/view', 'id' => $model->user->userProfile->id], [
                            'title' => AmosCommunity::t('amoscommunity', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->user->userProfile->surnameName])
                        ]);
                    }
                },
                'format' => 'html'
            ],
            'company' => [
                'label' => AmosEvents::txt('#participant_azienda'),
                'value' => function($model) use ($eventInvitationModel) {
                    $result = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    if(!empty($invitation)){
                        $result = $invitation->company;
                    }
                    return $result;
                }
            ],
            /*'status' => [
                'attribute' => 'status',
                'label' => AmosCommunity::t('amoscommunity', 'Status'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'Status'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'Status'),
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /*return AmosCommunity::t('amoscommunity', $model->status);
                }
            ],
            'role' => [
                'attribute' => 'role',
                'label' => AmosCommunity::t('amoscommunity', 'Role'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'Role'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'Role'),
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /*if ($model->role == 'COMMUNITY_MANAGER' && !empty($this->communityManagerRoleName)) {
                        return AmosCommunity::t('amoscommunity', $this->communityManagerRoleName);
                    }
                    return AmosCommunity::t('amoscommunity', $model->role);
                }
            ],*/
        ];

        $exportColumns = [
            'user.userProfile.nome',
            'user.userProfile.cognome',
            'user.email' => [
                'attribute' => 'user.email',
                'label' => AmosCommunity::t('amoscommunity', 'Email')
            ],
            'user.userProfile.codice_fiscale',
            /*'status' => [
                'attribute' => 'status',
                'label' => AmosCommunity::t('amoscommunity', 'Confirm status'),
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /*return AmosCommunity::t('amoscommunity', $model->status);
                }
            ],*/
            /*'invitation_accepted_at' => [
                'attribute' => 'invitation_accepted_at',
                'label' => AmosCommunity::t('amoscommunity', 'Confirm date'),
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /*return \Yii::$app->formatter->asDatetime($model->invitation_accepted_at, 'humanalwaysdatetime');
                }
            ]*/
        ];

        if($this->eventModel->has_tickets) {
            $exportColumns['company'] = [
                'label' => AmosEvents::txt('#participant_azienda'),
                'value' => function($model) use ($eventInvitationModel) {
                    $aziendaName = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    if(!empty($invitation)){
                        $aziendaName = $invitation['company'];
                    }
                    return $aziendaName;
                }
            ];
            $exportColumns['accred'] = [
                'label' => AmosEvents::txt('Accreditation list'),
                'value' => function($model) use ($eventInvitationModel, $eventAccreditationListModel) {
                    $accreditationName = "";
                    $accreditationListId = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id])['accreditation_list_id'];
                    if(!empty($accreditationListId)){
                        $accreditation = $eventAccreditationListModel::findOne(['id' => $accreditationListId]);
                        if(!empty($accreditation)) {
                            $accreditationName = $accreditation->title;
                        }
                    }
                    return $accreditationName;
                }
            ];
            $exportColumns['comp'] = [
                'label' => AmosEvents::txt('Companions'),
                'value' => function ($model) use ($eventInvitationModel, $eventParticipantCompanionModel) {
                    $companionsList = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    $companions = $eventParticipantCompanionModel::findAll(['event_invitation_id' => $invitation['id']]);

                    $firstForeach = true;
                    foreach ($companions as $companion) {
                        if(!$firstForeach) {
                            $companionsList .= ", ";
                        }
                        $companionsList .= $companion->nome . ' ' . $companion->cognome;
                        $firstForeach = false;
                    }
                    return $companionsList;
                }
            ];
            $exportColumns['ticketsent'] = [
                'label' => AmosEvents::txt('Tickets sent?'),
                'value' => function ($model) use ($eventInvitationModel) {
                    $response = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    if($invitation && !empty($invitation)) {
                        $response = $invitation['is_ticket_sent'] ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
                    }
                    return $response;
                }
            ];
            $exportColumns['downloadedat'] = [
                'label' => AmosEvents::txt('Ticket downloaded at'),
                'value' => function ($model) use ($eventInvitationModel) {
                    $dateTime = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    if($invitation && !empty($invitation)) {
                        $dateTime = $invitation['ticket_downloaded_at'];
                    }
                    return $dateTime;
                }
            ];
            $exportColumns['downloadedby'] = [
                'label' => AmosEvents::txt('Ticket downloaded by'),
                'value' => function ($model) use ($eventInvitationModel) {
                    $name = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    if($invitation && !empty($invitation['ticket_downloaded_by'])) {
                        $userProfile = UserProfile::findOne(['user_id' => $invitation['ticket_downloaded_by']]);
                        if($userProfile && !empty($userProfile)) {
                            $name = $userProfile->getNomeCognome();
                        }
                    }
                    return $name;
                }
            ];
            $exportColumns['attendant'] = [
                'label' => AmosEvents::txt('Attendant'),
                'value' => function ($model) use ($eventInvitationModel) {
                    $response = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    if($invitation && !empty($invitation)) {
                        $response = $invitation['presenza'] ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
                    }
                    return $response;
                }
            ];
            $exportColumns['attendancescan'] = [
                'label' => AmosEvents::txt('Attendance scanned at'),
                'value' => function ($model) use ($eventInvitationModel) {
                    $response = "";
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    if($invitation && !empty($invitation)) {
                        $response = $invitation['presenza_scansionata_il'];
                    }
                    return $response;
                }
            ];
        }
        
        if ($this->viewEmail) {
            $itemsMittente['email'] = [
                'label' => AmosCommunity::t('amoscommunity', 'Email'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'email'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'email'),
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    return $model->user->email;
                }
            ];
        }
        
        if ($this->viewInvitation) {
            /*$itemsMittente['invited_at'] = [
                'attribute' => 'invited_at',
                'label' => AmosCommunity::t('amoscommunity', '#invited_at'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', '#invited_at'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', '#invited_at'),
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /*return \Yii::$app->formatter->asDatetime($model->invited_at);
                }
            ];*/
            /*$itemsMittente['invitation_accepted_at'] = [
                'attribute' => 'invitation_accepted_at',
                'label' => AmosCommunity::t('amoscommunity', '#invitation_accepted_at'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', '#invitation_accepted_at'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', '#invitation_accepted_at'),
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /*return \Yii::$app->formatter->asDatetime($model->invitation_accepted_at);
                }
            ];*/
            if($this->eventModel->has_tickets) {
                // TODO ABILITA IN CASO SERVANO
                /*$itemsMittente['email'] = [
                    'label' => AmosEvents::txt('#participant_email'),
                    'value' => function($model) {
                        $aziendaName = "";
                        $invitation = EventInvitation::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                        if(!empty($invitation)){
                            $aziendaName = $invitation['email'];
                        }
                        return $aziendaName;
                    }
                ];*/
                /*$itemsMittente['company'] = [
                    'label' => AmosEvents::txt('#participant_azienda'),
                    'value' => function($model) {
                        $aziendaName = "";
                        $invitation = EventInvitation::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                        if(!empty($invitation)){
                            $aziendaName = $invitation['company'];
                        }
                        return $aziendaName;
                    }
                ];*/
                $itemsMittente['accreditationList'] = [
                    'label' => AmosEvents::txt('Accreditation list'),
                    'value' => function($model) use ($eventInvitationModel, $eventAccreditationListModel) {
                        $accreditationName = "";
                        $accreditationListId = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id])['accreditation_list_id'];
                        if(!empty($accreditationListId)){
                            $accreditation = $eventAccreditationListModel::findOne(['id' => $accreditationListId]);
                            if(!empty($accreditation)) {
                                $accreditationName = $accreditation->title;
                            }
                        }
                        return $accreditationName;
                    }
                ];
                $itemsMittente['spedito'] = [
                    'label' => AmosEvents::txt('Tickets sent?'),
                    'value' => function($model) use ($eventInvitationModel) {
                        $result = "";
                        $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                        if(!empty($invitation)){
                            $result = $invitation->is_ticket_sent ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
                        }
                        return $result;
                    }
                ];
                $itemsMittente['attendant'] = [
                    'label' => AmosEvents::txt('Attendant'),
                    'value' => function ($model) use ($eventInvitationModel) {
                        $response = "";
                        $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                        if($invitation && !empty($invitation)) {
                            $response = $invitation['presenza'] ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
                        }
                        return $response;
                    }
                ];
                $itemsMittente['downloadedat'] = [
                    'label' => AmosEvents::txt('Ticket downloaded at'),
                    'value' => function($model) use ($eventInvitationModel) {
                        $result = "";
                        $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                        if(!empty($invitation)){
                            $result = !empty($invitation->ticket_downloaded_at) ? $invitation->ticket_downloaded_at : "-";
                        }
                        return $result;
                    }
                ];
            }
            $itemsMittente['is_group'] = [
                'value' => function($model) use ($eventInvitationModel) {
                    $invitation = $eventInvitationModel::findOne(['user_id' => $model->user_id, 'event_id' => $this->eventModel->id]);
                    return $invitation->is_group;
                },
                'format' => 'boolean',
                'label' => AmosEvents::t('amosevents', "E' un gruppo")
            ];

            //SEATS MANAGEMENT
            if($this->eventModel->isSeatManagement()){
                $eventModel = $this->eventModel;
                $itemsMittente [] = [
                    'value' => function ($model) use ($eventModel, $eventSeatsModel) {
                        $seats = $eventSeatsModel::find()
                            ->andWhere(['event_id' => $eventModel->id])
                            ->andWhere(['user_id' => $model->user_id])->one();
                        if($seats) {
                            return $seats->getStringCoordinateSeat() .', '.$seats->getLabelStatus();
                        }
                        return '-';
                    },
                    'label' => AmosEvents::t('amosevents', 'Posto assegnato')
                ];
            }

            $itemsMittente['partner_of'] = [
                /*'attribute' => '',
                'format' => 'html',
                [*/
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'expandAllTitle' => 'Tasks',
                    'allowBatchToggle' => false,
                    'enableCache' => false,
                    'expandIcon' => "<span class=\"dash dash-users\"></span>",
                    'collapseIcon' => AmosEvents::txt('Close'),
                    'header' => AmosEvents::t("amosevents", "Accompagnatori"),//Module::t('amosproject_management', 'Expand / Collapse'),
                    'headerOptions' => [
                        'style' => 'white-space: nowrap;'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => Url::to(['/events/event/show-companions-list-only', 'eid' => $this->eventModel->id])
                /*],

                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    /*return '<div class=""></div>';
                    //return (!is_null($model->partnerOf) ? $model->partnerOf->userProfile->surnameName : '-');
                }*/
            ];
        }


        $isSubCommunity = !empty($model->getCommunityModel()->parent_id);
        
        //Merge additional solumns
        $itemsMittente = ArrayHelper::merge($itemsMittente, $this->additionalColumns);
        
        $actionColumnsTemplate = '{participantDetails}{downloadTickets}{markAsAttendant}';
        $loggedUserIsManager = false;
        if ($this->checkManager()) {
            $actionColumnsTemplate = '{assign-seat}{acceptUser}{rejectUser}{participantDetails}{change-participant-accreditation-list}{downloadTickets}{send-ticket}{markAsAttendant}{relationAttributeManage}{deleteRelation}';
            $loggedUserIsManager = true;
        }
        if ($this->forceActionColumns) {
            $actionColumnsTemplate = $this->actionColumnsTemplate;
        }

        $associateBtnDisabled = false;
        if ($model instanceof Community && $model->status != Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED && !$model->validated_once) {
            $associateBtnDisabled = true;
        }
        
        
        $query = !empty($this->showRoles)
            ? $model->getCommunityModel()->getCommunityUserMms()->andWhere(['role' => $this->showRoles])
            : $model->getCommunityModel()->getCommunityUserMms();
        
        $query->innerJoin('user_profile up', 'community_user_mm.user_id = up.user_id')
            ->andWhere(['up.attivo' => 1]);
        
        /*if (isset($_POST[$searchPostName])) {
            $searchName = $_POST[$searchPostName];
            if (!empty($searchName)) {
                if($this->eventModel->has_tickets) {
                    // QUERY RICERCA?
                    $query->innerJoin('event_invitation', "community_user_mm.user_id = event_invitation.user_id")
                        ->andWhere('community_user_mm.deleted_at IS NULL')
                        ->andWhere('event_invitation.deleted_at IS NULL')
                        ->andWhere("event_invitation.event_id = {$this->eventModel->id}")
                        ->andWhere(['or',
                            ['like', 'event_invitation.name', $searchName],
                            ['like', 'event_invitation.surname', $searchName],
                            ['like', "CONCAT( event_invitation.name , ' ', event_invitation.surname )", $searchName],
                            ['like', "CONCAT( event_invitation.surname , ' ', event_invitation.name )", $searchName]
                        ]);
                } else {
                    $query->andWhere('community_user_mm.deleted_at IS NULL')
                        ->andWhere(['or',
                            ['like', 'user_profile.nome', $searchName],
                            ['like', 'user_profile.cognome', $searchName],
                            ['like', "CONCAT( user_profile.nome , ' ', user_profile.cognome )", $searchName],
                            ['like', "CONCAT( user_profile.cognome , ' ', user_profile.nome )", $searchName]
                        ]);
                }
            }
        }*/

        $searchParamsArray = [];
        if(!empty(\Yii::$app->request->get("{$gridId}-search"))) {
            $get = \Yii::$app->request->get("{$gridId}-search");
            $searchParamsArray = $get;

            //pr(\Yii::$app->request->get());
            //pr($get);

            // Query aggiuntiva per ricerca su accompagnatori (bisogna comunque mostrare il partecipante
            // per poter visualizzare i risultati degli accompagnatori)
            $queryForCompanions = new Query();
            $queryForCompanions->select('community_user_mm.*')
                                    ->from('community_user_mm')
                                    ->innerJoin('user', 'community_user_mm.user_id = user.id AND user.deleted_at IS NULL')
                                    ->innerJoin('user_profile', 'user_profile.user_id = user.id')
                                    ->innerJoin('event_invitation as invitation', 'invitation.user_id = user.id AND invitation.deleted_at IS NULL')
                                    ->innerJoin('event_participant_companion as companion', 'companion.event_invitation_id = invitation.id AND companion.deleted_at IS NULL')
                                    ->andWhere(['community_user_mm.deleted_at' => null])
                                    ->andWhere(['user_profile.attivo' => 1])
                                    ->andWhere(['role' => $this->showRoles])
                                    ->andWhere(['community_user_mm.community_id' => $this->eventModel->community_id]);

            // Se vengono effettuate ricerche su campi non presenti in tabella degli accompagnatori
            // non effettuo la union delle due tabelle (altrimenti creo confusione tra i risultati)
            $joinQueryForCompanions = true;

            $query->innerJoin('event_invitation as invitation', 'invitation.user_id = community_user_mm.user_id AND invitation.deleted_at IS NULL');

            if(array_key_exists('nomeCognome', $get) && $get['nomeCognome'] != "") {
                $query->andWhere(['or',
                    ['like', 'invitation.name', '%' . $get['nomeCognome'] . '%', false],
                    ['like', 'invitation.surname', '%' . $get['nomeCognome'] . '%', false],
                    ['like', "CONCAT( invitation.name , ' ', invitation.surname )", '%' . $get['nomeCognome'] . '%', false],
                    ['like', "CONCAT( invitation.surname , ' ', invitation.name )", '%' . $get['nomeCognome'] . '%', false]
                ]);
                $queryForCompanions->andWhere(['or',
                    ['like', 'companion.nome', '%' . $get['nomeCognome'] . '%', false],
                    ['like', 'companion.cognome', '%' . $get['nomeCognome'] . '%', false],
                    ['like', "CONCAT( companion.nome , ' ', companion.cognome )", '%' . $get['nomeCognome'] . '%', false],
                    ['like', "CONCAT( companion.cognome , ' ', companion.nome )", '%' . $get['nomeCognome'] . '%', false]
                ]);
            }

            if(array_key_exists('azienda', $get) && $get['azienda'] != "") {
                $query->andWhere(['like', 'invitation.company', '%' . $get['azienda'] . '%', false]);
                $queryForCompanions->andWhere(['like', 'companion.azienda', '%' . $get['azienda'] . '%', false]);
            }

            if(array_key_exists('listaAccreditamento', $get) && $get['listaAccreditamento'] != "") {
                if($get['listaAccreditamento'] == '_WITHOUTACCREDITATIONLIST') {
                    $query->andWhere('invitation.accreditation_list_id IS NULL');
                    $queryForCompanions->andWhere('companion.event_accreditation_list_id IS NULL');
                } else {
                    $query->andWhere(['invitation.accreditation_list_id' => intval($get['listaAccreditamento'])]);
                    $queryForCompanions->andWhere(['companion.event_accreditation_list_id' => intval($get['listaAccreditamento'])]);
                }
            }

            if(array_key_exists('bigliettoSpedito', $get) && $get['bigliettoSpedito'] != "") {
                $query->andWhere(['invitation.is_ticket_sent' => intval($get['bigliettoSpedito'])]);
                $joinQueryForCompanions = false;
            }

            if(array_key_exists('presenza', $get) && $get['presenza'] != "") {
                $query->andWhere(['invitation.presenza' => intval($get['presenza'])]);
                $queryForCompanions->andWhere(['companion.presenza' => intval($get['presenza'])]);
            }

            if(array_key_exists('scaricatoIl', $get) && $get['scaricatoIl'] != "") {
                $query->andWhere("invitation.ticket_downloaded_at between '{$get['scaricatoIl']}' and '{$get['scaricatoIl']} 23:59:59'");
                $joinQueryForCompanions = false;
            }

            if($joinQueryForCompanions) {
                $query->union($queryForCompanions->createCommand()->rawSql);
            }

        }
        $contextObject = $model;
        $community = $model->getCommunityModel();
        $roles = $contextObject->getContextRoles();
        $rolesArray = [];
        foreach ($roles as $role) {
            $rolesArray[$role] = $role;
        }
        $rolesArray['EVENTS_CHECK_IN'] = 'EVENTS_CHECK_IN';
        $event = $this->eventModel;
        $insass = ($inviteUserOfcommunityParent && !$isSubCommunity && $customInvitationForm) || (!$inviteUserOfcommunityParent && $customInvitationForm);
        $widget = M2MWidget::widget([
            'model' => $model->getCommunityModel(),
            'modelId' => $model->getCommunityModel()->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'disableAssociaButton' => true,
            'additionalAssociaButtonEnabled' => true,
            'exportMittenteConfig' => [
                'exportEnabled' => false,
                'exportColumns' => $exportColumns
            ],
            'forceListRender' => true,
            'targetUrlParams' => $this->targetUrlParams,
            'gridId' => $gridId,
            'firstGridSearch' => true,
            'isModal' => $this->enableModal,
            'createAdditionalAssociateButtonsEnabled' => $this->showAdditionalAssociateButton,
            'disableCreateButton' => true,
            'btnAssociaLabel' => AmosCommunity::t('amoscommunity', 'Invite users'),
            'btnAssociaClass' => 'btn btn-tools-primary' . ($associateBtnDisabled ? ' disabled' : ''),
            'btnAdditionalAssociateLabel' => AmosEvents::txt('Iscrivi partecipante'),
            'actionColumnsTemplate' => $actionColumnsTemplate,
            'deleteRelationTargetIdField' => 'user_id',
            'targetUrl' => $insass ? '/community/community/insass-m2m' : '/community/community/associa-m2m',
            'overrideAdditionalTargetUrl' => [
                '/events/event/event-signup',
                'eid' => $this->eventModel->id,
                'emptyFields' => 'true'
            ],
            'createNewTargetUrl' => '/admin/user-profile/create',
            'moduleClassName' => AmosCommunity::className(),
            'targetUrlController' => 'community',
            'postName' => 'Community',
            'postKey' => 'user',
            'viewSearch' => false, // temporaneo, per fix pjax ricerca
            'firstGridSearch' => false, // temporaneo, per fix pjax ricerca
            'permissions' => [
                'add' => $this->addPermission,
                'manageAttributes' => $this->manageAttributesPermission //UpdateCommunitiesManagerRule::className()//$model->getCommunityModel()->isCommunityManager()
            ],
            'actionColumnsButtons' => [
                'confirmManager' => function ($url, $model) {
                    /** @var CommunityUserMm $model */
                    $status = $model->status;
                    $createUrlParams = [
                        '/community/community/confirm-manager',
                        'communityId' => $model->community_id,
                        'userId' => $model->user_id,
                        'managerRole' => $this->model->getManagerRole()
                    ];
                    $btn = '';
                    if ($status == CommunityUserMm::STATUS_MANAGER_TO_CONFIRM) {
                        $btn = Html::a(
                            AmosIcons::show('check-circle', ['class' => 'btn btn-tool-secondary']),
                            Yii::$app->urlManager->createUrl($createUrlParams), ['title' => AmosCommunity::t('amoscommunity', 'Confirm manager')]);
                    }
                    return $btn;
                },
                // CHANGED ICON
                'acceptUser' => function ($url, $model) {
                    /** @var CommunityUserMm $model */
                    $status = $model->status;
                    $createUrlParams = ['/community/community/accept-user', 'communityId' => $model->community_id, 'userId' => $model->user_id];
                    $btn = '';
                    if ($status == CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER) {
                        $btn = Html::a(
                            AmosIcons::show('check-circle', ['class' => '']),//AmosCommunity::t('amoscommunity', 'Accept user'),
                            Yii::$app->urlManager->createUrl($createUrlParams), ['class' => 'btn btn-tools-primary', 'title' => AmosCommunity::t('amoscommunity', 'Accept user')]);
                    }
                    return $btn;
                },
                // CHANGED ICON
                'rejectUser' => function ($url, $model) {
                    /** @var CommunityUserMm $model */
                    $btn = '';
                    $createUrlParams = ['/community/community/reject-user', 'communityId' => $model->community_id, 'userId' => $model->user_id];
                    if ($model->status == CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER) {
                        $btn = Html::a(
                            AmosIcons::show('close-circle', ['class' => '']),//AmosCommunity::t('amoscommunity', 'Reject user'),
                            Yii::$app->urlManager->createUrl($createUrlParams), ['class' => 'btn btn-tools-primary', 'title' => AmosCommunity::t('amoscommunity', 'Reject user'),]);
                    }
                    return $btn;
                },
                'participantDetails' => function ($url, $model) use ($eventInvitationModel) {
                    $btn = '';
                    $invitation = $eventInvitationModel::findOne(['event_id' => $this->eventModel->id, 'user_id' => $model->user_id]);
                    if (!empty($invitation)) {
                        $url = Yii::$app->urlManager->createUrl(['/events/event/participant-detail', 'eid' => $this->eventModel->id, 'iid' => $invitation->id]);
                        if (EventsUtility::hasPrivilegesLoggedUser($this->eventModel)) { // || Yii::$app->user->id == $model->user_id) {
                            $btn = Html::a(
                                AmosIcons::show('file', ['class' => '']),
                                $url,
                                ['class' => 'btn btn-tools-primary', 'title' => AmosEvents::txt('Visualizza scheda partecipante'), 'data-pjax' => '0',]);
                        }
                    }
                    return $btn;
                },
                'change-participant-accreditation-list' => function ($url, $model) use ($eventInvitationModel, $eventAccreditationListModel) {
                    if($this->eventModel->has_tickets) {
                        if (\Yii::$app->user->can($this->manageAttributesPermission)) {
                            if ($this->checkManager()) {
                                $invitation = $eventInvitationModel::findOne(['event_id' => $this->eventModel->id, 'user_id' => $model->user_id]);

                                $btn = '';

                                if($invitation) {

                                    $modalId = 'change-participant-accreditation-list-modal-' . $invitation->id;
                                    $selectId = 'accreditation-list-participant-' . $invitation->id;
                                    Modal::begin([
                                        'header' => AmosEvents::txt("Select accreditation list"),
                                        'id' => $modalId,
                                    ]);

                                    $accreditationTypesModels = $eventAccreditationListModel::find()->andWhere(['event_id' => $this->eventModel->id])->orderBy('position ASC')->all();
                                    $accreditationTypes = [
                                        null => AmosEvents::txt("Not set"),
                                    ];

                                    $url = "/events/event/change-participant-accreditation-list?id=" . $invitation->id;

                                    foreach ($accreditationTypesModels as $atModel) {
                                        $accreditationTypes[$atModel->id] = $atModel->title;
                                    }

                                    echo Html::tag('div', Select::widget([
                                        'auto_fill' => true,
                                        'hideSearch' => true,
                                        'theme' => 'bootstrap',
                                        'data' => $accreditationTypes,
                                        'model' => $invitation,
                                        'attribute' => 'accreditation_list_id',
                                        'value' => isset($accreditationTypes[$invitation->accreditation_list_id]) ? AmosEvents::txt($accreditationTypes[$invitation->accreditation_list_id])
                                            : null,
                                        'options' => [
                                            //                                    'prompt' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                                            'disabled' => false,
                                            'id' => $selectId
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false,
                                        ]
                                    ]), ['class' => 'm-15-0']);

                                    echo Html::tag('div',
                                        Html::a(AmosCommunity::t('amoscommunity', 'Cancel'),
                                            null,
                                            ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
                                        . Html::a(AmosCommunity::t('amoscommunity', 'Save'),
                                            null,
                                            [
                                                'class' => 'btn btn-tools-primary',
                                                'onclick' => "
                                        
                                        $.ajax({
                                            url : '$url', 
                                            type: 'POST',
                                            async: true,
                                            data: { 
                                                accreditationListId: $('#$selectId').val()
                                            },
                                            success: function(response) {
                                               $('#$modalId').modal('hide');
                                               $('#reset-search-btn-$this->gridId').click();
                                               
                                               var urlToReload = '{$this->pageUrl}';
                                               if(window.getUrlForPjax) {
                                                    urlToReload = window.getUrlForPjax();
                                               }
                                               
                                               setTimeout(function() {
                                                   $.pjax.reload({url: urlToReload, container: '#$this->pjaxId', async: false, timeout: false});
                                                   if(window.sendAllTickets) {sendAllTickets();}
                                               }, 350);
                                           }
                                        });
                                    return false;
                                "
                                            ]),
                                        ['class' => 'pull-right m-15-0']
                                    );
                                    //                        echo $this->render('@vendor/open20/amos-community/src/views/community/change-user-role', ['model' => $model]);
                                    Modal::end();

                                    $btn = Html::a(
                                        Html::a(AmosIcons::show('assignment-o', ['class' => '']),
                                            null, [
                                                'class' => 'btn btn-tools-primary btn-tools-primary-text',
                                                'style' => 'margin-left:3px',
                                                'title' => AmosEvents::txt('Select accreditation list'),
                                                'data-toggle' => 'modal',
                                                'data-target' => '#' . $modalId,
                                                'onclick' => 'checkSelect2Init("' . $modalId . '", "' . $selectId . '");'
                                            ]));

                                }

                                return $btn;
                            }
                        }
                    }
                    return "";
                },
                'downloadTickets' => function ($url, $model) use ($eventInvitationModel) {
                    if($this->eventModel->has_tickets && $model->status == CommunityUserMm::STATUS_ACTIVE){
                        $invitation = $eventInvitationModel::findOne(['event_id' => $this->eventModel->id, 'user_id' => $model->user_id]);
                        $btn = '';
                        if (!empty($invitation)) {
                            /** @var CommunityUserMm $model */
                            $url = Yii::$app->urlManager->createUrl(['/events/event/download-tickets', 'eid' => $this->eventModel->id, 'iid' => $invitation->id, 'code' => $invitation->code]);
                            if ($this->checkManager() || Yii::$app->user->id == $model->user_id) {
                                $btn = Html::a(
                                    AmosIcons::show('download', ['class' => '']),
                                    $url, ['class' => 'btn btn-tools-primary', 'title' => AmosEvents::txt('Download Tickets'), 'target' => '_blank', 'data-pjax' => '0',]);
                            }
                        }
                        return $btn;
                    } else {
                        return '';
                    }
                },
                'send-ticket' => function ($url, $model) use ($eventInvitationModel) {
                    $btn = '';
                    if($this->checkManager() && $model->status == CommunityUserMm::STATUS_ACTIVE &&
                        ($this->eventModel->registration_date_begin == null || date('Y-m-d H:i:s') >= date($this->eventModel->registration_date_begin)) &&
                        $this->eventModel->has_tickets) {
                        $invitation = $eventInvitationModel::findOne(['event_id' => $this->eventModel->id, 'user_id' => $model->user_id]);
                        if($invitation) {
                            $btn = Html::a(
                                AmosIcons::show('mail-send', ['class' => '']),
                                "/events/event/send-ticket?eid={$this->eventModel->id}&iid={$invitation->id}",
                                [
                                    'class' => 'btn btn-navigation-primary',
                                    'title' => AmosEvents::txt('Send ticket'),
                                    'data-pjax' => 0
                                ]
                            );
                        }
                    }
                    return $btn;
                },
                'markAsAttendant' => function ($url, $model) use ($eventInvitationModel) {
                    $btn = '';
                    if($this->eventModel->has_tickets) {
                        if($this->eventModel->begin_date_hour == null || (
                            (strtotime("now") >= strtotime($this->eventModel->begin_date_hour . ' - 6 hours')) && (date('Y-m-d H:i:s') <= date($this->eventModel->end_date_hour))
                            )
                        ) {
                            if (EventsUtility::hasPrivilegesLoggedUser($this->eventModel)) {
                                $errorTranslatedMessage = AmosEvents::txt("Error");
                                $markAttendanceTranslatedMessage = AmosEvents::txt("Mark as attendant");

                                $invitation = $eventInvitationModel::findOne(['event_id' => $this->eventModel->id, 'user_id' => $model->user_id]);

                                //if(!$invitation->presenza) {
                                if (!empty($invitation)) {
                                    $confirmationUrl = Yii::$app->urlManager->createUrl(['/events/event/register-participant', 'eid' => $this->eventModel->id, 'pid' => $model->user_id, 'iid' => $invitation->id, 'booleanResponse' => true]);
                                    $removeAttendanceUrl = Yii::$app->urlManager->createUrl(['/events/event/remove-participant-attendance', 'eid' => $this->eventModel->id, 'pid' => $model->user_id, 'iid' => $invitation->id, 'booleanResponse' => true]);

                                    $btnId = "attendant-eid_{$this->eventModel->id}-pid_{$model->user_id}-iid_{$invitation->id}";
                                    $divRemoveAttendanceId = "remove-attendance-{$btnId}";

                                    $btn = Html::a(
                                            AmosIcons::show('pin-account', ['class' => '']) . ' ' . AmosIcons::show('circle-o', ['class' => '']),//AmosIcons::show('pin-account', ['class' => '']) . ' ' . Html::tag('span', $markAttendanceTranslatedMessage, ['class' => 'message']),
                                            null,
                                            [
                                                'id' => $btnId,
                                                'class' => 'btn btn-tools-primary',
                                                'title' => AmosEvents::txt('Mark as attendant'),
                                                'style' => ($invitation->presenza ? "display:none;" : ""),
                                                'onClick' => "
                                                    $('#error-$btnId').remove();
                                                    $.ajax({
                                                        url : '$confirmationUrl', 
                                                        type: 'GET',
                                                        async: true,
                                                        success: function(response) {
                                                            if(response) {
                                                                $('#$btnId').hide();
                                                                $('#$divRemoveAttendanceId').show();
                                                                
                                                                var urlToReload = '{$this->pageUrl}';
                                                                   if(window.getUrlForPjax) {
                                                                        urlToReload = window.getUrlForPjax();
                                                                   }
                                                                
                                                                $.pjax.reload({url: urlToReload, container: '#$this->pjaxId', async: false, timeout: false});
                                                                if(window.sendAllTickets) {sendAllTickets();}
                                                            } else {
                                                                $('#$btnId span.message').remove();
                                                                $('#$btnId').append('<span id=\x22error-$btnId\x22> $errorTranslatedMessage</span>');
                                                                $('#$btnId').show();
                                                            }
                                                       }
                                                    });
                                                return false;
                                                "
                                            ]
                                        ) . Html::a(
                                            AmosIcons::show('pin-account', ['class' => '']) . ' ' . AmosIcons::show('check-circle', ['class' => '']),//AmosIcons::show('pin-account', ['class' => '']) . ' ' . Html::tag('span', AmosEvents::txt('Remove attendance'), ['class' => 'message']),
                                            null,
                                            [
                                                'id' => $divRemoveAttendanceId,
                                                'class' => 'btn btn-secondary',
                                                'style' => (!($invitation->presenza) ? "display:none;" : ""),
                                                'title' => AmosEvents::txt('Remove attendance'),
                                                'onClick' => "
                                                $('#error-$btnId').remove();
                                                $.ajax({
                                                    url : '$removeAttendanceUrl', 
                                                    type: 'GET',
                                                    async: true,
                                                    success: function(response) {
                                                        if(response) {
                                                            $('#$divRemoveAttendanceId').hide();
                                                            $('#$btnId').show();
                                                            
                                                            var urlToReload = '{$this->pageUrl}';
                                                               if(window.getUrlForPjax) {
                                                                    urlToReload = window.getUrlForPjax();
                                                               }
                                                                   
                                                            $.pjax.reload({url: urlToReload, container: '#$this->pjaxId', async: false, timeout: false});
                                                            if(window.sendAllTickets) {sendAllTickets();}
                                                        } else {
                                                            $('#$divRemoveAttendanceId span.message').remove();
                                                            $('#$divRemoveAttendanceId').append('<span id=\x22error-$btnId\x22> $errorTranslatedMessage</span>');
                                                            $('#$divRemoveAttendanceId').show();
                                                        }
                                                   }
                                                });
                                            return false;
                                            "
                                            ]
                                        );
                                }
                                /*} else {
                                    $btn = Html::a(AmosIcons::show('pin-account', ['class' => '']) . $attendantTranslatedMessage, null, ['class' => 'btn btn-tools-primary', 'disabled' => 'disabled']);
                                }*/
                            }
                        }
                    }
                    return $btn;
                },
                'relationAttributeManage' => function ($url, $model) use ($rolesArray, $community, $contextObject, $loggedUserIsManager) {
                    $btn = '';
//                    $createUrlParamsRole = ['/community/community/manage-m2m-attributes', 'id' => $model->community_id, 'targetId' => $model->id];
                    $url = Yii::$app->urlManager->createUrl($createUrlParamsRole = ['/community/community/change-user-role', 'communityId' => $model->community_id, 'userId' => $model->user_id]);
                    if ($loggedUserIsManager) {
                        if (!is_null($model->role) && ($model->status != CommunityUserMm::STATUS_WAITING_OK_USER)) {
                            // If an user is community creator, it will be not possible to change his role in participant, unless logged user is admin
//                            if (($community->created_by != $model->user_id) || $loggedUser->can("ADMIN")) {
                                $modalId = 'change-user-role-modal-' . $model->user_id;
                                $selectId = 'community_user_mm-role-' . $model->user_id;
                                Modal::begin([
                                    'header' => AmosCommunity::t('amoscommunity', 'Manage role and permission'),
                                    'id' => $modalId,
                                ]);
                                
                                echo Html::tag('div', Select::widget([
                                    'auto_fill' => true,
                                    'hideSearch' => true,
                                    'theme' => 'bootstrap',
                                    'data' => $rolesArray,
                                    'model' => $model,
                                    'attribute' => 'role',
                                    'value' => isset($rolesArray[$model->role]) ? AmosCommunity::t('amoscommunity',
                                        $rolesArray[$model->role]) : $rolesArray[$contextObject->getBaseRole()],
                                    'options' => [
//                                    'prompt' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                                        'disabled' => false,
                                        'id' => $selectId
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false,
                                    ]
                                ]), ['class' => 'm-15-0']);
                                
                                echo Html::tag('div',
                                    Html::a(AmosCommunity::t('amoscommunity', 'Cancel'),
                                        null,
                                        ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
                                    . Html::a(AmosCommunity::t('amoscommunity', 'Save'),
                                        null,
                                        [
                                            'class' => 'btn btn-tools-primary',
                                            'onclick' => "
                                    $('#$modalId a.btn').addClass('disabled');
                                    $.ajax({
                                        url : '$url', 
                                        type: 'POST',
                                        async: true,
                                        data: { 
                                            role: $('#$selectId').val()
                                        },
                                        success: function(response) {
                                           //$('#$modalId').modal('hide');
                                           $('#reset-search-btn-$this->gridId').click();
                                           window.location.reload('{$this->pageUrl}');
                                       }
                                    });
                                return false;
                            "
                                        ]),
                                    ['class' => 'pull-right m-15-0']
                                );
//                        echo $this->render('@vendor/open20/amos-community/src/views/community/change-user-role', ['model' => $model]);
                                Modal::end();
                                
                                $btn = Html::a(
                                    AmosIcons::show('refresh-sync'), //AmosCommunity::t('amoscommunity', 'Change role'),
                                    null, [
                                    'class' => 'btn btn-tools-primary btn-tools-primary-text',
                                    'title' => AmosCommunity::t('amoscommunity', 'Change role'),
                                    'data-toggle' => 'modal',
                                    'data-target' => '#' . $modalId,
                                    'onclick' => 'checkSelect2Init("' . $modalId . '", "' . $selectId . '");'
                                ]);


//                        $btn = Html::a(
//                            AmosCommunity::t('amoscommunity', 'Change role'),
//                            Yii::$app->urlManager->createUrl($createUrlParamsRole), ['class' => 'btn btn-tools-primary font08']);
//                            }
                        }
                    }
                    return $btn;
                },
                'deleteRelation' => function ($url, $model) use ($eventInvitationModel) {
                    $invitation = $eventInvitationModel::findOne(['event_id' => $this->eventModel->id, 'user_id' => $model->user_id]);
                    if($invitation) {
                        $url = '/events/event/remove-signup-to-event';
                        $urlDelete = Yii::$app->urlManager->createUrl([
                            $url,
                            'eid' => $this->eventModel->id,
                            'iid' => $invitation->id,
                            'code' => $invitation->code,
                            'autoRemove' => (!EventsUtility::checkManager($this->model) || (EventsUtility::checkManager($this->model) && $invitation->user_id == \Yii::$app->user->id))
                        ]);
                        $loggedUser = Yii::$app->getUser();
                        if ($loggedUser->can('COMMUNITY_UPDATE', ['model' => $this->model])) {
                            $btnDelete = Html::a(
                                AmosIcons::show('close', ['class' => '']),
                                $urlDelete,
                                ['title' => AmosCommunity::t('amoscommunity', 'Delete'),
                                    //'data-confirm' => $this->delete_member_message,
                                    'class' => 'btn btn-danger-inverse'
                                ]
                            );
                            if (($this->eventModel->created_by == $model->user_id) && !$loggedUser->can("ADMIN")) {
                                $btnDelete = '';
                            }
                        } else {
                            $btnDelete = '';
                        }
                        return $btnDelete;
                    } else {
                        return '';
                    }
                },
                'assign-seat' => function ($url, $model) use ($event, $eventSeatsModel) {
                    $btn = '';
                    if(EventsUtility::checkManager($event)) {
                        if ($event->isSeatManagement() && $model->status == CommunityUserMm::STATUS_ACTIVE) {
                            $hasSeat = $eventSeatsModel::find()
                                ->andWhere(['event_id' => $event->id])
                                ->andWhere(['user_id' => $model->user_id])->count();
                            if (!$hasSeat) {
                                $btn = Html::a(
                                    AmosIcons::show('seat'), ['/events/event/assign-seat', 'id' => $event->id, 'user_id' => $model->user_id],
                                    ['title' => AmosCommunity::t('amosevents', 'Assegna posto'),
                                        'class' => 'btn btn-tools-secondary',
                                        'style' => 'background-color: #297a38; border-color:#297a38',
                                    ]
                                );
                            } else {
                                $btn = Html::a(
                                    AmosIcons::show('close-circle-o'), ['/events/event/remove-seat', 'id' => $event->id, 'user_id' => $model->user_id],
                                    ['title' => AmosCommunity::t('amosevents', 'Libera posto'),
                                        'class' => 'btn btn-tools-secondary',
                                        'style' => 'background-color: #297a38; border-color:#297a38',
                                        'data-confirm' => AmosEvents::t('amosevents', 'Sei sicuro di liberare il posto?')
                                    ]
                                );
                            }
                        }
                    }
                    return $btn;
                }
            ],
            'itemsMittente' => $itemsMittente,
        ]);

        // FIX LINK "ISCRIVI PARTECIPANTE" E ALTRI FIX
        // fatti per non toccare l'm2m widget
        // - fix id chiodato nel btn-additional
        // - rimuove pulsante associa non piu necessario e che non puo' essere tolto dalle config
        $sendTicketsLabel = AmosEvents::txt('Send tickets');
        $downloadParticipantsListLabel = AmosEvents::txt('Download participants list');

        $additionalButtons = '';

        if($this->eventModel->isSeatManagement()) {
            $additionalButtons .= "<a class='btn btn-navigation-primary' href='/events/event/event-signup-group?eid={$this->eventModel->id}'>" . AmosEvents::t('amosevents', 'Iscrivi gruppi') . "</a>";
        }
        $additionalButtons .= "<a class=\\\"btn btn-navigation-primary\\\" href=\\\"/events/event/send-tickets-massive?eid={$this->eventModel->id}\\\" title=\\\"{$sendTicketsLabel}\\\" data-pjax=\\\"0\\\">{$sendTicketsLabel}</a><a class=\\\"btn btn-navigation-primary\\\" href=\\\"/events/event/download-participants-excel?eid={$this->eventModel->id}\\\" title=\\\"{$downloadParticipantsListLabel}\\\" data-pjax=\\\"0\\\"><span class=\\\"am am-download\\\"></span></a>";
        $this->view->registerJs(<<<JS
            
    window.getWidgetCurrentPageUrl = function() {
        return '{$this->pageUrl}';
    }

    $("#{$gridId} a#m2m-widget-btn-additional-associate").attr('data-pjax', '0');
    $("#{$gridId} a#m2m-widget-btn-additional-associate").attr('target', '_blank');
    

JS
                , \yii\web\View::POS_LOAD);

        $searchContainer = "";
        // Appende bottone ricerca se abilitata (e renderizza la partial della ricerca per
        // appenderla al return per la visualizzazione del widget più sotto)
        if($this->showSearch && $this->checkManager()) {
            $searchToggleButton = "<span class=\\\"btn btn-tools-primary show-hide-element am am-search pull-right\\\" onclick=\\\"if ($('#{$gridId}-search-container').is(':hidden')) { $('#{$gridId}-search-container').show(); } else { $('#{$gridId}-search-container').hide(); }\\\"> </span>";
            $this->view->registerJs(<<<JS
                $("#{$gridId} .container-tools").append("{$searchToggleButton}");

                window.getUrlForPjax = function() {
                    if($("#{$gridId}-search-container").is(":visible")) {
                        return '{$this->pageUrl}?' + $("#{$gridId}-search-container-form").serialize();
                    } else {
                        return '{$this->pageUrl}';
                    }
                }

JS
        );
            $searchContainer = $this->render('_search_for_participant_widget', [
                'container' => $gridId,
                'pjaxId' => $this->pjaxId,
                'event' => $this->eventModel,
                'resetUrl' => $this->pageUrl,
                'searchParamsArray' => $searchParamsArray,
            ]);
        }

            // FIX fatti per non toccare l'm2m widget
        // Se abilitati, creo nuovi pulsante per download excel partecipanti e invio massivo biglietti
        if($this->enableAdditionalButtons && $this->checkManager()) {
            $this->view->registerJs(<<<JS
                $("#{$gridId} .container-tools").append("{$additionalButtons}");
JS
                , \yii\web\View::POS_LOAD);
        }
        
        $message = $associateBtnDisabled ? AmosCommunity::t('amoscommunity', '#invite_users_disabled_msg') : '';
        return $message . "<div id='" . $gridId . "'>" . $searchContainer . $widget . "</div>";
    }
    
    /**
     * 
     * @return boolean
     */
    private function checkManager()
    {
        $ret = false;
        
        if (!$this->checkManagerRole) {
            return true;
        }
        $communityUtil = new CommunityUtil();
        if(!is_null($this->model->community)){
            $isOperator = EventsUtility::isUserEventOperator(\Yii::$app->user->id, $this->model);

            $ret =  $communityUtil->isManagerLoggedUser($this->model->community) || $isOperator;
        }
        return $ret;
    }
}
