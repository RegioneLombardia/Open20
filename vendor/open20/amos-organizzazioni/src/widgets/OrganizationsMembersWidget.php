<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\widgets
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\widgets;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\JsUtility;
use open20\amos\organizzazioni\controllers\ProfiloController;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\organizzazioni\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\PjaxAsset;

/**
 * Class OrganizationsMembersWidget
 * @package open20\amos\organizzazioni\widgets
 */
class OrganizationsMembersWidget extends Widget
{
    /**
     * @var Profilo $model
     */
    public $model = null;

    /**
     * @var Module $organizationsModule
     */
    public $organizationsModule = null;

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
    public $addPermission = 'ADD_EMPLOYEE_TO_ORGANIZATION_PERMISSION';

    /**
     * @var string $manageAttributesPermission
     */
    public $manageAttributesPermission = 'MANAGE_EMPLOYEE_TO_ORGANIZATION_PERMISSION';

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
     * @var null
     */
    public $userStatusAssociate = null;

    /**
     * @var string $gridId
     */
    public $gridId = 'organizations-employees-grid';

    /**
     * @var bool $enableModal
     */
    public $enableModal = false;

    /**
     * @var string $delete_member_message
     */
    public $delete_member_message = false;

    /**
     * @var string
     */
    public $organizationManagerRoleName = '';

    /**
     * @var array
     */
    public $filterForRole = null;

    /**
     * @var string
     */
    public $labelAssociate = '';
    /**
     * @var string
     */
    public $labelCreateNewBtn = '';

    /**
     * @var string
     */
    public $redirectUrlAfterAssociate = '';

    /**
     * @var null
     */
    public $filterCategoryInvitation = null;

    /**
     * 
     * @var type
     */
    public $isUpdate = null;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }

        $this->delete_member_message = $this->delete_member_message
            ? $this->delete_member_message
            : Module::t('amosorganizzazioni', '#organizations_members_widget_delete_message');
        $this->organizationsModule = Module::instance();
    }

    /**
     * @param string $field
     * @return string
     */
    protected function throwErrorMessage($field)
    {
        return Module::t(
            'amosorganizzazioni',
            'Wrong widget configuration: missing field {field}',
            ['field' => $field]
        );
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $gridId = $this->gridId
            . (!empty($this->showRoles)
                ? '-'
                    . implode('-', $this->showRoles)
                : ''
            );

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
        $params['organizationManagerRoleName'] = $this->organizationManagerRoleName;
        $params['redirectUrlAfterAssociate'] = $this->redirectUrlAfterAssociate;
        $params['filterForRole'] = $this->filterForRole;
        $params['userStatusAssociate'] = $this->userStatusAssociate;
        $params['isUpdate'] = $this->isUpdate;

        $url = \Yii::$app->urlManager->createUrl([
            '/organizzazioni/profilo/organization-employees',
            'id' => $model->id,
            'classname' => $model->className(),
            'params' => $params
        ]);

        $searchPostName = 'searchEmployeeName'
            . (!empty($this->showRoles)
                ? implode('', $this->showRoles)
                : ''
            );

        $js = JsUtility::getSearchM2mFirstGridJs($gridId, $url, $searchPostName);
        PjaxAsset::register($this->getView());
        $this->getView()->registerJs($js, View::POS_LOAD);

        /** @var UserProfile $emptyUserProfile */
        $emptyUserProfile = AmosAdmin::instance()->createModel('UserProfile');
        $emptyUser = new User();

        $itemsMittente = [
            'userImage' => [
                'label' => $emptyUserProfile->getAttributeLabel('userProfileImage'),
                'headerOptions' => [
                    'id' => Module::t('amosorganizzazioni', 'user_image'),
                ],
                'contentOptions' => [
                    'id' => Module::t('amosorganizzazioni', 'user_image'),
                ],
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return UserCardWidget::widget(['model' => $model->user->userProfile]);
                }
            ],
            'name' => [
                'attribute' => 'user.userProfile.surnameName',
                'label' => $emptyUserProfile->getAttributeLabel('surnameName'),
                'headerOptions' => [
                    'id' => Module::t('amosorganizzazioni', 'surname_name'),
                ],
                'contentOptions' => [
                    'headers' => Module::t('amosorganizzazioni', 'surname_name'),
                ],
                'format' => 'html',
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    $surnameName = $model->user->userProfile->surnameName;
                    return Html::a(
                        $surnameName,
                        $model->user->userProfile->getFullViewUrl(),
                        [
                            'title' => Module::t(
                                'amosorganizzazioni',
                                'Apri il profilo di {nome_profilo}',
                                ['nome_profilo' => $surnameName]
                            )
                        ]
                    );
                }
            ],
            [
                'attribute' => 'user.email',
                'label' => $emptyUser->getAttributeLabel('email')
            ],
        ];

        $exportColumns = [
			'user.userProfile.nameSurname' => [
                'attribute' => 'user.userProfile.nameSurname',
                'label' => Module::t('amosorganizzazioni', 'Nome Cognome'),
				'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return Module::t('amosorganizzazioni', $model->user->userProfile->nome." ".$model->user->userProfile->cognome);
                }
            ],
            'user.email' => [
                'attribute' => 'user.email',
                'label' => Module::t('amosorganizzazioni', 'Email')
            ],
            'user.userProfile.codice_fiscale',
        ];

        if ($this->organizationsModule->viewStatusEmployees) {
            $statusLabel = Module::t('amosorganizzazioni', '#profilo_user_mm_status_label');
            $itemsMittente['status'] = [
                'attribute' => 'status',
                'label' => $statusLabel,
                'headerOptions' => [
                    'id' => $statusLabel,
                ],
                'contentOptions' => [
                    'headers' => $statusLabel,
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return Module::t('amosorganizzazioni', $model->status);
                }
            ];
            $exportColumns['status'] = [
                'attribute' => 'status',
                'label' => Module::t('amosorganizzazioni', 'Confirm status'),
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return Module::t('amosorganizzazioni', $model->status);
                }
            ];
        }

        if ($this->organizationsModule->viewRoleEmployees) {
            $roleLabel = Module::t('amosorganizzazioni', '#profilo_user_mm_role_label');
            $itemsMittente['role'] = [
                'attribute' => 'role',
                'label' => $roleLabel,
                'headerOptions' => [
                    'id' => $roleLabel,
                ],
                'contentOptions' => [
                    'headers' => $roleLabel,
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return Module::t('amosorganizzazioni', $model->role);
                }
            ];
        }

        $viewEmailEmployees = false;
        if (isset($this->organizationsModule->viewEmailEmployees)) {
            $viewEmailEmployees = $this->organizationsModule->viewEmailEmployees;
        }

        if ($viewEmailEmployees || ($this->viewEmail)) {
            $itemsMittente['email'] = [
                'label' => Module::t('amosorganizzazioni', 'Email'),
                'headerOptions' => [
                    'id' => Module::t('amosorganizzazioni', 'email'),
                ],
                'contentOptions' => [
                    'headers' => Module::t('amosorganizzazioni', 'email'),
                ],
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return $model->user->email;
                }
            ];
        }

        $isSubOrganization = !empty($model->parent_id);

        //Merge additional solumns
        $itemsMittente = ArrayHelper::merge($itemsMittente, $this->additionalColumns);

        $actionColumnsTemplate = '';
        if ($this->checkManager()) {
            $actionColumnsTemplate = '{acceptUser}{rejectUser}{deleteRelation}';
        }
        if ($this->forceActionColumns) {
            $actionColumnsTemplate = $this->actionColumnsTemplate;
        }

        $actionColumnButtons = [
            'acceptUser' => function ($url, $model) {
                /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                $status = $model->status;
                $createUrlParams = [
                    '/organizzazioni/profilo/accept-user',
                    'profiloId' => $model->profilo_id,
                    'userId' => $model->user_id
                ];
                $btn = '';
                if ($status == ProfiloUserMm::STATUS_WAITING_OK_ORGANIZATION_REFEREE) {
                    $btn = Html::a(
                        Module::t('amosorganizzazioni', 'Accept user'),
                        Yii::$app->urlManager->createUrl($createUrlParams),
                        ['class' => 'btn btn-primary', 'style' => 'font-size: 0.8em']
                    );
                }
                return $btn;
            },
            'rejectUser' => function ($url, $model) {
                /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                $btn = '';
                $createUrlParams = [
                    '/organizzazioni/profilo/reject-user',
                    'profiloId' => $model->profilo_id,
                    'userId' => $model->user_id
                ];
                if ($model->status == ProfiloUserMm::STATUS_WAITING_OK_ORGANIZATION_REFEREE) {
                    $btn = Html::a(
                        Module::t('amosorganizzazioni', 'Reject user'),
                        Yii::$app->urlManager->createUrl($createUrlParams),
                        ['class' => 'btn btn-primary', 'style' => 'font-size: 0.8em']
                    );
                }
                return $btn;
            },
            'deleteRelation' => function ($url, $model) {
                /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                $url = '/organizzazioni/profilo/elimina-m2m';
                $profilo = $model->profilo;
                $targetId = $model->user_id;
                $urlDelete = Yii::$app->urlManager->createUrl(
                    [
                        $url,
                        'id' => $profilo->id,
                        'targetId' => $targetId,
                        'redirectAction' => \yii\helpers\Url::current()
                    ]
                );
                $loggedUser = Yii::$app->getUser();
                if ($loggedUser->can('ADD_EMPLOYEE_TO_ORGANIZATION_PERMISSION', ['model' => $this->model])) {
                    $btnDelete = Html::a(
                        AmosIcons::show('close', ['class' => '']),
                        $urlDelete,
                        [
                            'title' => Module::t('amosorganizzazioni', '#organizations_members_widget_delete_title'),
                            'data-confirm' => $this->delete_member_message,
                            'class' => 'btn btn-danger-inverse'
                        ]
                    );
                    if (($profilo->created_by == $model->user_id) && !$loggedUser->can("ADMIN")) {
                        $btnDelete = '';
                    }
                } else {
                    $btnDelete = '';
                }
                return $btnDelete;
            },
        ];

        /** @var \open20\amos\events\AmosEvents $eventsModule */
        $eventsModule = Yii::$app->getModule('events');
        $adminModule = AmosAdmin::instance();
        $moodleModule = \Yii::$app->getModule('moodle');

        $eventColumnsTemplate = '';
        $moodleColumnsTemplate = '';
        if (!is_null($eventsModule) && $eventsModule->hasMethod('getInviteUserToEventWidget') && $adminModule->enableInviteUserToEvent) {
            $eventColumnsTemplate = '{getInviteUserToEventWidget}';
            $actionColumnButtons['getInviteUserToEventWidget'] = [$this, 'getInviteUserToEventWidget'];
        }

        /** @var \open20\amos\moodle\AmosMoodle $moodleModule */
        if (!is_null($moodleModule) && $moodleModule->hasMethod('getSubscribeUserToFADCourseWidget') && $moodleModule->enableSubscribeUserToFADCourse) {
            $moodleColumnsTemplate = '{getSubscribeUserToFADCourseWidget}';
            $actionColumnButtons['getSubscribeUserToFADCourseWidget'] = [$this, 'getSubscribeUserToFADCourseWidget'];
        }

        $actionColumnsTemplate = $eventColumnsTemplate . $moodleColumnsTemplate . $actionColumnsTemplate;

        /** @var ProfiloController $appController */
        $appController = Yii::$app->controller;
        $query = $appController->getOrganizationEmployeesQuery($model, true, $this->showRoles);

        if (isset($_POST[$searchPostName])) {
            $searchName = $_POST[$searchPostName];
            if (!empty($searchName)) {
                $userProfileTable = UserProfile::tableName();
                $query->andWhere(
                    [
                        'or',
                        ['like', $userProfileTable . '.nome', $searchName],
                        ['like', $userProfileTable . '.cognome', $searchName],
                        [
                            'like',
                            "CONCAT( " . $userProfileTable . ".nome , ' ', " . $userProfileTable . ".cognome )",
                            $searchName
                        ],
                        [
                            'like',
                            "CONCAT( " . $userProfileTable . ".cognome , ' ', " . $userProfileTable . ".nome )",
                            $searchName
                        ]
                    ]
                );
            }
        }
        $roleName = $this->filterForRole;
        if (!empty($roleName)) {
            $query->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
                ->andWhere(['auth_assignment.item_name' => $roleName]);
        }

        /** @var \open20\amos\invitations\Module $invitationsModule */
        $invitationsModule = Yii::$app->getModule('invitations');
        $invitationsModulePresent = !is_null($invitationsModule);
        if ($invitationsModulePresent) {
            Yii::$app->session->set($invitationsModule::beginCreateNewSessionKey(), Url::current());
        }

        $createNewTargetUrl = [
            '/invitations/invitation/index' . (\Yii::$app->user->can('INVITATIONS_ADMINISTRATOR') ? '-all' : '') . '/',
            'moduleName' => Module::getModuleName()
        ];
        
        if (!empty($this->filterCategoryInvitation)) {
            $createNewTargetUrl['category'] = $this->filterCategoryInvitation;
        }
        
        if ($this->organizationsModule->enableUniqueSecretCodeForInvitation) {
            $createNewTargetUrl['contextModelId'] = $model->unique_secret_code;
            $createNewTargetUrl['registerAction'] = 'register-with-code';
        } else {
            $createNewTargetUrl['contextModelId'] = $model->id;
        }

        $targetUrlParams = [];
        if (!empty($roleName)) {
            $targetUrlParams = ['role_name' => $roleName];
        }
        
        if (!empty($this->redirectUrlAfterAssociate)) {
            $targetUrlParams = ArrayHelper::merge($targetUrlParams, ['redirectUrlAfterAssociate' => $this->redirectUrlAfterAssociate]);
        }
        
        if (!empty($this->userStatusAssociate)) {
            $targetUrlParams['userStatus'] = $this->userStatusAssociate;
        }
        

        $widget = M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'exportMittenteConfig' => [
                'exportEnabled' => true,
                'exportColumns' => $exportColumns
            ],
            'forceListRender' => true,
            'gridId' => $gridId,
            'firstGridSearch' => true,
            'isModal' => $this->enableModal,
            'createAdditionalAssociateButtonsEnabled' => false,
            'disableCreateButton' => (
                !$invitationsModulePresent
                || !Yii::$app->user->can('INVITATION_CREATE')
            ),
            'checkPermWithNewMethod' => false,
            'btnAssociaLabel' => empty($this->labelAssociate)
                ? Module::t('amosorganizzazioni', 'Associate employees')
                : $this->labelAssociate,
            'btnAssociaClass' => 'btn btn-primary m-l-5',
            'createNewBtnLabel' => empty($this->labelCreateNewBtn)
                ? Module::t('amosorganizzazioni', 'Invite employees')
                : $this->labelCreateNewBtn,
            'btnAdditionalAssociateLabel' => Module::t('amosorganizzazioni', 'Invite employees'),
            'actionColumnsTemplate' => $actionColumnsTemplate,
            'deleteRelationTargetIdField' => 'user_id',
            'targetUrl' => '/organizzazioni/profilo/associa-m2m',
            'targetUrlParams' => $targetUrlParams,
            'additionalTargetUrl' => '/organizzazioni/profilo/additional-associate-m2m',
            'createNewTargetUrl' => $createNewTargetUrl,
            'moduleClassName' => Module::class,
            'targetUrlController' => 'profilo',
            'postName' => 'Profilo',
            'postKey' => 'user',
            'permissions' => [
                'add' => $this->addPermission,
                'manageAttributes' => $this->manageAttributesPermission
            ],
            'actionColumnsButtons' => $actionColumnButtons,
            'itemsMittente' => $itemsMittente,
        ]);

        return '<div id="'
            . $gridId
            . '" data-pjax-container="'
            . $gridId
            . '-pjax data-pjax-timeout="10000">'
            . $widget
            . '</div>';
    }

    /**
     * @return bool
     */
    private function checkManager()
    {
        return true; // TODO FUTURE USE. For now return true. See community widget.
    }

    /**
     * @param string|array $url
     * @param ProfiloUserMm $model
     * @return string
     * @throws \Exception
     */
    public function getInviteUserToEventWidget($url, $model)
    {
        if ($model->status == ProfiloUserMm::STATUS_ACTIVE) {
            /** @var \open20\amos\events\AmosEvents $eventsModule */
            $eventsModule = Yii::$app->getModule('events');
            
            return $eventsModule->getInviteUserToEventWidget($model->user->userProfile);
        }

        return '';
    }

    /**
     *
     * @param type $url
     * @param type $model
     * @return type
     */
    public function getSubscribeUserToFADCourseWidget($url, $model)
    {
        /** @var \open20\amos\moodle\AmosMoodle $moodleModule */
        $moodleModule = \Yii::$app->getModule('moodle');
        if (
            !is_null($moodleModule)
            && $moodleModule->hasMethod('getSubscribeUserToFADCourseWidget')
            && $moodleModule->enableSubscribeUserToFADCourse
        ) {
            if ($model->status == ProfiloUserMm::STATUS_ACTIVE) {
                return $moodleModule->getSubscribeUserToFADCourseWidget(
                    $model->user->userProfile,
                    $model
                );
            }
        }

        return '';
    }

}
