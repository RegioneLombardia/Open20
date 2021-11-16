<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\widgets;

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityContextInterface;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\Record;
use open20\amos\core\utilities\JsUtility;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\PjaxAsset;

/**
 * Class CommunityMembersWidget
 * @package open20\amos\community\widgets
 */
class CommunityMembersWidget extends Widget
{
    /**
     * @var Community $model
     */
    public $model = null;
    
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
    public $viewInvitation = false;
    
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
     * @var array $targetUrlInvitation
     */
    public $targetUrlInvitation = null;
    
    /**
     * @var array $invitationModule
     */
    public $invitationModule = null;
    
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
    
    /**
     *
     * @var bool $externalInvitationUsers
     */
    public $externalInvitationUsers = true;
    
    /**
     *
     * @var array $exportMittenteConfig 
     */
    public $exportMittenteConfig = [];
    
    /**
     *  @var string $additionalButtons
     */
    public $additionalButtons = '';
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
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
        
        $customInvitationForm = AmosCommunity::instance()->customInvitationForm;
        $inviteUserOfcommunityParent = AmosCommunity::instance()->inviteUserOfcommunityParent;
        
        $gridId = $this->gridId . (!empty($this->showRoles) ? '-' . implode('-', $this->showRoles) : '');
        $model = $this->model;
        $communityModuleName = AmosCommunity::getModuleName();
        $communityContextClassName = $model->context;
        /** @var CommunityContextInterface $communityContext */
        $communityContext = Yii::createObject($communityContextClassName);
        $contextModelModuleName = $communityContext->getPluginModule();
        /** @var AmosModule $contextModelModuleObj */
        $contextModelModuleObj = Yii::$app->getModule($contextModelModuleName);
        
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
        
        $params['targetUrlInvitation'] = $this->targetUrlInvitation;
        $params['invitationModule'] = $this->invitationModule;
        
        
        $url = \Yii::$app->urlManager->createUrl([
            '/community/community/community-members',
            'id' => $model->id,
            'classname' => $model->className(),
            'params' => $params
        ]);
        $searchPostName = 'searchMemberName' . (!empty($this->showRoles) ? implode('', $this->showRoles) : '');
        
        $js = JsUtility::getSearchM2mFirstGridJs($gridId, $url, $searchPostName);
        PjaxAsset::register($this->getView());
        $this->getView()->registerJs($js, View::POS_LOAD);
        
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
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    return Html::a($model->user->userProfile->surnameName, ['/admin/user-profile/view', 'id' => $model->user->userProfile->id], [
                        'title' => AmosCommunity::t('amoscommunity', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->user->userProfile->surnameName])
                    ]);
                },
                'format' => 'html'
            ],
            'status' => [
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
                    return AmosCommunity::t('amoscommunity', $model->status);
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
                'value' => function ($model) use ($communityModuleName, $contextModelModuleName, $contextModelModuleObj) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    if ($model->role == 'COMMUNITY_MANAGER' && !empty($this->communityManagerRoleName)) {
                        return AmosCommunity::t('amoscommunity', $this->communityManagerRoleName);
                    }
                    if ($communityModuleName == $contextModelModuleName) {
                        return AmosCommunity::t('amoscommunity', $model->role);
                    } else {
                        return BaseAmosModule::t($contextModelModuleObj->getAmosUniqueId(), $model->role);
                    }
                }
            ],
        ];
        
        $exportColumns = [
            'user.userProfile.nome',
            'user.userProfile.cognome',
            'user.email' => [
                'attribute' => 'user.email',
                'label' => AmosCommunity::t('amoscommunity', 'Email')
            ],
            'user.userProfile.codice_fiscale',
            'partnerOf.userProfile.nomeCognome' => [
                'attribute' => 'partnerOf.userProfile.nomeCognome',
                'label' => AmosCommunity::t('amoscommunity', 'Invited by')
            ],
            'status' => [
                'attribute' => 'status',
                'label' => AmosCommunity::t('amoscommunity', 'Confirm status'),
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    return AmosCommunity::t('amoscommunity', $model->status);
                }
            ],
            'invitation_accepted_at' => [
                'attribute' => 'invitation_accepted_at',
                'label' => AmosCommunity::t('amoscommunity', 'Confirm date'),
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    return \Yii::$app->formatter->asDatetime($model->invitation_accepted_at, 'humanalwaysdatetime');
                }
            ]
        ];

        $view_email_partecipants = false;
        if (isset(Yii::$app->getModule('community')->view_email_partecipants)) {
            $view_email_partecipants = Yii::$app->getModule('community')->view_email_partecipants;
        }
        
        if (($view_email_partecipants && $this->checkManager()) || ($this->viewEmail)) {
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
            $itemsMittente['invited_at'] = [
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
                    return \Yii::$app->formatter->asDatetime($model->invited_at);
                }
            ];
            $itemsMittente['invitation_accepted_at'] = [
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
                    return \Yii::$app->formatter->asDatetime($model->invitation_accepted_at);
                }
            ];
            $itemsMittente['partner_of'] = [
                'attribute' => 'invitation_partner_of',
                'label' => AmosCommunity::t('amoscommunity', '#invitation_partner_of'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', '#invitation_partner_of'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', '#invitation_partner_of'),
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\community\models\CommunityUserMm $model */
                    return (!is_null($model->partnerOf) ? $model->partnerOf->userProfile->surnameName : '-');
                }
            ];
        }
        $isSubCommunity = !empty($model->getCommunityModel()->parent_id);
        
        //Merge additional solumns
        $itemsMittente = ArrayHelper::merge($itemsMittente, $this->additionalColumns);
        
        $actionColumnsTemplate = '';
        if ($this->checkManager()) {
            $actionColumnsTemplate = '{acceptUser}{rejectUser}{relationAttributeManage}{deleteRelation}';
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
        
        $query
            ->innerJoin('user_profile up', 'community_user_mm.user_id = up.user_id')
            ->andWhere(['up.attivo' => 1]);
        
        if (isset($_POST[$searchPostName])) {
            $searchName = $_POST[$searchPostName];
            if (!empty($searchName)) {
                $query->andWhere('community_user_mm.deleted_at IS NULL')
                    ->andWhere(['or',
                        ['like', 'user_profile.nome', $searchName],
                        ['like', 'user_profile.cognome', $searchName],
                        ['like', "CONCAT( user_profile.nome , ' ', user_profile.cognome )", $searchName],
                        ['like', "CONCAT( user_profile.cognome , ' ', user_profile.nome )", $searchName]
                    ]);
            }
        }

        if(empty(Yii::$app->request->getQueryParams()['sort'])){
            $query->orderBy("user_profile.cognome, user_profile.nome");
        }
        
        $contextObject = $model;
        $community = $model->getCommunityModel();
        $roles = $contextObject->getContextRoles();
        $rolesArray = [];
        foreach ($roles as $role) {
            $rolesArray[$role] = $role;
        }
        
        $insass = ($inviteUserOfcommunityParent && !$isSubCommunity && $customInvitationForm) || (!$inviteUserOfcommunityParent && $customInvitationForm);             
        
        
        
        
        
        
        $configM2MW = [
            'btnInvitationLabel' => AmosCommunity::t('amoscommunity', 'Invita Utenti Esterni'),
            'btnInvitationClass' => 'btn btn-primary' . ($associateBtnDisabled ? ' disabled' : ''),
            'targetUrlInvitation' => $this->targetUrlInvitation,
            'invitationModule' => $this->invitationModule,
            'externalInvitationEnabled' => (\Yii::$app->getModule('community')->externalInvitationUsers == true) 
                ? (Yii::$app->user->can('ADMIN') || CommunityUtil::isLoggedCommunityManager())
                : false,
            
            'model' => $model->getCommunityModel(),
            'modelId' => $model->getCommunityModel()->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'targetUrlParams' => $this->targetUrlParams,
            'gridId' => $gridId,
            'firstGridSearch' => true,
            'isModal' => $this->enableModal,
            'createAdditionalAssociateButtonsEnabled' => $this->showAdditionalAssociateButton,
            'additionalButtons' => $this->additionalButtons,
            'disableCreateButton' => true,
            'btnAssociaLabel' => AmosCommunity::t('amoscommunity', 'Invite users'),
            'btnAssociaClass' => 'btn btn-primary' . ($associateBtnDisabled ? ' disabled' : ''),
            'btnAdditionalAssociateLabel' => AmosCommunity::t('amoscommunity', 'Invite internal users'),
            'actionColumnsTemplate' => $actionColumnsTemplate,
            'deleteRelationTargetIdField' => 'user_id',
            'targetUrl' => $insass ? '/community/community/insass-m2m' : '/community/community/associa-m2m',
            'additionalTargetUrl' => '/community/community/additional-associate-m2m',
            'createNewTargetUrl' => '/admin/user-profile/create',
            'moduleClassName' => AmosCommunity::className(),
            'targetUrlController' => 'community',
            'postName' => 'Community',
            'postKey' => 'user',
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
                'acceptUser' => function ($url, $model) {
                    /** @var CommunityUserMm $model */
                    $status = $model->status;
                    $createUrlParams = ['/community/community/accept-user', 'communityId' => $model->community_id, 'userId' => $model->user_id];
                    $btn = '';
                    if ($status == CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER) {
                        $btn = Html::a(
                            AmosCommunity::t('amoscommunity', 'Accept user'),
                            Yii::$app->urlManager->createUrl($createUrlParams), ['class' => 'btn btn-primary', 'style' => 'font-size: 0.8em']);
                    }
                    return $btn;
                },
                'rejectUser' => function ($url, $model) {
                    /** @var CommunityUserMm $model */
                    $btn = '';
                    $createUrlParams = ['/community/community/reject-user', 'communityId' => $model->community_id, 'userId' => $model->user_id];
                    if ($model->status == CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER) {
                        $btn = Html::a(
                            AmosCommunity::t('amoscommunity', 'Reject user'),
                            Yii::$app->urlManager->createUrl($createUrlParams), ['class' => 'btn btn-primary', 'style' => 'font-size: 0.8em']);
                    }
                    return $btn;
                },
                'relationAttributeManage' => function ($url, $model) use ($rolesArray, $community, $contextObject) {
                    $btn = '';
                    $loggedUser = Yii::$app->getUser();
//                    $createUrlParamsRole = ['/community/community/manage-m2m-attributes', 'id' => $model->community_id, 'targetId' => $model->id];
                    $url = Yii::$app->urlManager->createUrl($createUrlParamsRole = ['/community/community/change-user-role', 'communityId' => $model->community_id, 'userId' => $model->user_id]);
                    if (\Yii::$app->user->can($this->manageAttributesPermission)) {
                        if (!is_null($model->role) && ($model->status != CommunityUserMm::STATUS_WAITING_OK_USER)) {
                            // If an user is community creator, it will be not possible to change his role in participant, unless logged user is admin
                            if (($community->created_by != $model->user_id) || $loggedUser->can("ADMIN")) {
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
                                            'class' => 'btn btn-primary',
                                            'onclick' => "
                                    
                                    $.ajax({
                                        url : '$url', 
                                        type: 'POST',
                                        async: true,
                                        data: { 
                                            role: $('#$selectId').val()
                                        },
                                        success: function(response) {
                                           $('#$modalId').modal('hide');
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
                                    AmosCommunity::t('amoscommunity', 'Change role'),
                                    null, [
                                    'class' => 'btn btn-tools-secondary btn-tools-secondary-text',
                                    'title' => AmosCommunity::t('amoscommunity', 'Change role'),
                                    'data-toggle' => 'modal',
                                    'data-target' => '#' . $modalId,
                                    'onclick' => 'checkSelect2Init("' . $modalId . '", "' . $selectId . '");'
                                ]);


//                        $btn = Html::a(
//                            AmosCommunity::t('amoscommunity', 'Change role'),
//                            Yii::$app->urlManager->createUrl($createUrlParamsRole), ['class' => 'btn btn-primary font08']);
                            }
                        }
                    }
                    return $btn;
                },
                'deleteRelation' => function ($url, $model) {
                    $url = '/community/community/elimina-m2m';
                    $community = \open20\amos\community\models\Community::findOne($model->community_id);
                    $targetId = $model->user_id;
                    $urlDelete = Yii::$app->urlManager->createUrl([
                        $url,
                        'id' => $community->id,
                        'targetId' => $targetId
                    ]);
                    $loggedUser = Yii::$app->getUser();
                    if ($loggedUser->can('COMMUNITY_UPDATE', ['model' => $this->model])) {
                        $btnDelete = Html::a(
                            AmosIcons::show('close', ['class' => '']),
                            $urlDelete,
                            ['title' => AmosCommunity::t('amoscommunity', 'Delete'),
                                'data-confirm' => $this->delete_member_message,
                                'class' => 'btn btn-danger-inverse'
                            ]
                        );
                        if (($community->created_by == $model->user_id) && !$loggedUser->can("ADMIN")) {
                            $btnDelete = '';
                        }
                    } else {
                        $btnDelete = '';
                    }
                    return $btnDelete;
                },
            ],
            'itemsMittente' => $itemsMittente,
        ];

        //TODO : rimuovere una volta taggato amos-core.
        $tmp = new M2MWidget(['model' => $model->getCommunityModel(),
            'modelId' => $model->getCommunityModel()->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'targetUrl' => $insass ? '/community/community/insass-m2m' : '/community/community/associa-m2m']);

        if ($tmp->hasProperty('exportMittenteConfig')) {
            if (empty($this->exportMittenteConfig)) {
                $this->exportMittenteConfig =  [
                    'exportEnabled' => true,
                    'exportColumns' => $exportColumns
                ];
            }
            $configM2MW['exportMittenteConfig'] = $this->exportMittenteConfig;
        }
        
        $widget = M2MWidget::widget($configM2MW);

        $message = $associateBtnDisabled ? AmosCommunity::t('amoscommunity', '#invite_users_disabled_msg') : '';
        return $message . "<div id='" . $gridId . "' data-pjax-container='" . $gridId . "-pjax' data-pjax-timeout=\"1000\"  class=\"table-responsive\">" . $widget . "</div>";
    }
    
    private function checkManager()
    {
        if (!$this->checkManagerRole) {
            return true;
        }
        $communityUtil = new CommunityUtil();
        return $communityUtil->isManagerLoggedUser($this->model);
    }
}
