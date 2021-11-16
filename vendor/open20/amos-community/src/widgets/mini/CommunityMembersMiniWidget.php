<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\widgets\mini;

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\JsUtility;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\PjaxAsset;

/**
 * Class CommunityMembersWidget
 * @package open20\amos\community\widgets
 */
class CommunityMembersMiniWidget extends Widget
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
     * @var string $title The widget title
     */
    public $title = '';
    
    public $itemsSenderPageSize = 20;
    
    public $isUpdate = true;
    
    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        
        if (!$this->title) {
            $this->title = AmosCommunity::t('amoscommunity', 'Participants');
        }
        
        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }
        
        if (($this->model instanceof Community)) {
            $this->enableModal = true;
        }
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
        if (!$this->model->isNewRecord) {
            return $this->renderWidget();
        } else {
            return Html::tag('h2', $this->title) . Html::tag('div', AmosCommunity::t('amoscommunity', '#community_member_mini_widget_new_form'), ['class' => 'no-items']);
        }
    }
    
    
    /**
     * @param $model
     */
    private function registerWidgetJs($model)
    {
        $countParticipants = $model->getCommunityUsers()->count();
        if ($countParticipants) {
            $this->getView()->registerJs(<<<JS
    
            var quantity = "$countParticipants";
            var section_title = $('#section-member').find("h2");
        
            section_title.append(" (" + quantity + ")");
            if(quantity == 0){
                section_title.addClass("section-disabled");
            }
JS
            );
        }
    }
    
    /**
     * @return string
     */
    protected function renderWidget()
    {
        $customInvitationForm = AmosCommunity::instance()->customInvitationForm;
        $inviteUserOfcommunityParent = AmosCommunity::instance()->inviteUserOfcommunityParent;
        
        
        $gridId = $this->gridId . (!empty($this->showRoles) ? '-' . implode('-', $this->showRoles) : '');
        $model = $this->model;
        $params = [];
        $params['showRoles'] = $this->showRoles;
        $params['showAdditionalAssociateButton'] = $this->showAdditionalAssociateButton;
        $params['additionalColumns'] = $this->additionalColumns;
        $params['viewEmail'] = $this->viewEmail;
        $params['checkManagerRole'] = $this->checkManagerRole;
        $params['addPermission'] = $this->addPermission;
        $params['manageAttributesPermission'] = $this->manageAttributesPermission;
        $params['forceActionColumns'] = $this->forceActionColumns;
        $params['actionColumnsTemplate'] = $this->actionColumnsTemplate;
        $params['viewM2MWidgetGenericSearch'] = $this->viewM2MWidgetGenericSearch;
        $params['targetUrlParams'] = $this->targetUrlParams;
        $params['enableModal'] = $this->enableModal;
        $params['isUpdate'] = $this->isUpdate;
        
        
        $url = \Yii::$app->urlManager->createUrl([
            '/community/community/community-members-min',
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
                'label' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \open20\amos\admin\models\UserProfile $userProfile */
                    $userProfile = $model->user->getProfile();
                    return UserCardWidget::widget(['model' => $userProfile]);
                }
            ],
        ];
            
        if (CommunityUtil::loggedUserIsCommunityManager($this->model->id)) {
            $itemsMittente = ['Item' => [
                    'value' => function ($model) {
                        return $this->render('_item', [
                                    'model' => $model,
                                    'checkCM' => true
                        ]);
                    },
                    'label' => false,
                    'format' => 'raw'
                ],
            ];
        } else {
            $itemsMittente = ['Item' => [
                    'value' => function ($model) {
                        return $this->render('_item', [
                                    'model' => $model,
                                    'checkCM' => false
                        ]);
                    },
                    'label' => false,
                    'format' => 'raw'
                ],
            ];
        }        

        if ($this->viewEmail) {
            $itemsMittente['email'] = [
                'label' => AmosCommunity::t('amoscommunity', 'Email'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'email'),
                ],
                'contentOptions' => [
                    'headers' => '',
                ],
                'value' => function ($model) {
                    /** @var CommunityUserMm $model */
                    return $model->user->email;
                }
            ];
        }
        $isSubCommunity = !empty($model->getCommunityModel()->parent_id);
        
        //Merge additional solumns
        $itemsMittente = ArrayHelper::merge($itemsMittente, $this->additionalColumns);
        
        $actionColumnsTemplate = '';
        if ($this->checkManager()) {
            $actionColumnsTemplate = !$this->isUpdate ? '' : '{acceptUser}{rejectUser}{relationAttributeManage}{deleteRelation}';
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
        
        $contextObject = $model;
        $community = $model->getCommunityModel();
        $roles = $contextObject->getContextRoles();
        $rolesArray = [];
        foreach ($roles as $role) {
            $rolesArray[$role] = $role;
        }
        
        $insass = ($inviteUserOfcommunityParent && !$isSubCommunity && $customInvitationForm) || (!$inviteUserOfcommunityParent && $customInvitationForm);
        $widget = \open20\amos\core\forms\editors\m2mWidget\M2MWidget::widget([
            'model' => $model->getCommunityModel(),
            'modelId' => $model->getCommunityModel()->id,
            'modelData' => $query,
            'titleWidget' => !$this->isUpdate ? '' : $this->title,
            'showPageSummary' => false,
            'showHeader' => false,
//            'showPager' => false,
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'targetUrlParams' => $this->targetUrlParams,
            'gridId' => $gridId,
            'firstGridSearch' => true,
            'itemsSenderPageSize' => $this->itemsSenderPageSize,
            'isModal' => $this->enableModal,
            'layoutMittente' => "{toolbarMittenteMini}\n{itemsMittente}\n{footerMittente}",
            'createAdditionalAssociateButtonsEnabled' => $this->showAdditionalAssociateButton,
            'disableCreateButton' => true,
            'disableAssociaButton' => (!$this->isUpdate || ($model->status != Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED)),
            'btnAssociaLabel' => AmosCommunity::t('amoscommunity', '#community_member_mini_widget_btn_label'),
            'btnAssociaClass' => 'btn btn-primary btn-m2m' . ($associateBtnDisabled ? ' disabled' : ''),
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
                'manageAttributes' => $this->isUpdate && $this->manageAttributesPermission //UpdateCommunitiesManagerRule::className()//$model->getCommunityModel()->isCommunityManager()
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
                            AmosIcons::show('close', ['class' => 'btn btn-icon']),
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
                            AmosIcons::show('account-add', ['class' => 'btn btn-icon']),
                            Yii::$app->urlManager->createUrl($createUrlParams),
                            ['title' => AmosCommunity::t('amoscommunity', 'Accept user')]);
                    }
                    return $btn;
                },
                'rejectUser' => function ($url, $model) {
                    /** @var CommunityUserMm $model */
                    $btn = '';
                    $createUrlParams = ['/community/community/reject-user', 'communityId' => $model->community_id, 'userId' => $model->user_id];
                    if ($model->status == CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER) {
                        $btn = Html::a(
                            AmosIcons::show('block-alt', ['class' => 'btn btn-icon']),
                            Yii::$app->urlManager->createUrl($createUrlParams),
                            ['title' => AmosCommunity::t('amoscommunity', 'Reject user')]);
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
                                    'value' => (isset($rolesArray[$model->role]) ?
                                        AmosCommunity::t('amoscommunity', $rolesArray[$model->role]) :
                                        AmosCommunity::t('amoscommunity', $rolesArray[$contextObject->getBaseRole()])),
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
                                            'class' => 'btn btn-navigation-primary',
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
                                           $('#reset-search-btn-$this->gridId').click();
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
                                    AmosIcons::show('swap', ['class' => 'btn btn-icon']),
                                    null, [
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
                            AmosIcons::show('close', ['class' => 'btn btn-icon']),
                            $urlDelete,
                            ['title' => AmosCommunity::t('amoscommunity', 'Delete'),
                                'data-confirm' => Yii::t('amoscommunity', 'Are you sure to remove this user?'),
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
        ]);
        
        $this->registerWidgetJs($model);
        $message = $associateBtnDisabled ? AmosCommunity::t('amoscommunity', '#invite_users_disabled_msg') : '';
        
        $viewAllModal = $this->renderViewAllModal($query);
        return
            $message
            . "<div id='" . $gridId . "' data-pjax-container='" . $gridId . "-pjax' data-pjax-timeout=\"1000\" class=\"table-responsive\">" . $widget . "</div>"
            . $viewAllModal;
    }
    
    /**
     * @param $query
     * @return string
     */
    public function renderViewAllModal($query)
    {
        if (!$this->isUpdate) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
            
            if ($query->count() > $dataProvider->pagination->getPageSize()) {
                $viewAllButton = "<div class='col-sm-12 text-center' data-toggle='modal' data-target='view-all-members'>"
                    . Html::a(AmosCommunity::t('amoscommunity', '#view_all'), 'javascript:void(0);', [
                        'data-toggle' => 'modal',
                        'data-target' => '#view-all-members'
                    ])
                    . "</div>";
                
                return $viewAllButton
                    . $this->render('_view_all', ['dataProvider' => $dataProvider]);
            }
        }
        return '';
    }
    
    /**
     * @return bool
     */
    private function checkManager()
    {
        if (!$this->checkManagerRole) {
            return true;
        }
        $communityUtil = new CommunityUtil();
        return $communityUtil->isManagerLoggedUser($this->model);
    }
}
