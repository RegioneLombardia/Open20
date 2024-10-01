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
use open20\amos\admin\models\UserProfileArea;
use open20\amos\admin\models\UserProfileRole;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\AmosUser;
use open20\amos\core\utilities\JsUtility;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\organizzazioni\Module;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\PjaxAsset;

/**
 * Class UserNetworkWidgetOrganizzazioni
 * @package open20\amos\organizzazioni\widgets
 */
class UserNetworkWidgetOrganizzazioni extends Widget
{
    /**
     * @var int $userId
     */
    public $userId = null;

    /**
     * @var bool|false true if we are in edit mode, false if in view mode or otherwise
     */
    public $isUpdate = false;

    /**
     * @var string $gridId
     */
    public $gridId = 'user-organizzazioni-grid';

    /**
     * @var AmosAdmin $adminModule
     */
    private $adminModule;

    /**
     * @var Module $organizationsModule
     */
    private $organizationsModule;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->adminModule = AmosAdmin::instance();
        $this->organizationsModule = Module::instance();

        if (is_null($this->userId)) {
            throw new \Exception(Module::t('amosorganizzazioni', '#Missing_user_id'));
        }
    }

    /**
     * @return string
     */
    public static function getSearchPostName()
    {
        return 'searchProfiloName';
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $gridId = $this->gridId;
        $url = \Yii::$app->urlManager->createUrl([
            '/' . Module::getModuleName() . '/profilo/user-network',
            'userId' => $this->userId,
            'isUpdate' => $this->isUpdate
        ]);

        $js = JsUtility::getSearchM2mFirstGridJs($gridId, $url, static::getSearchPostName());
        PjaxAsset::register($this->getView());
        $this->getView()->registerJs($js, View::POS_LOAD);

        $query = $this->getModelDataQuery(static::getSearchPostName());

        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');

        /** @var UserProfile $model */
        $model = $userProfileModel::findOne(['user_id' => $this->userId]);

        /** @var AmosUser $loggedUser */
        $loggedUser = Yii::$app->getUser();
        $loggedUserId = $loggedUser->id;
        $this->isUpdate = $this->isUpdate
            && (
                ($loggedUserId == $model->user_id)
                || Yii::$app->user->can('AMMINISTRATORE_ORGANIZZAZIONI')
            );

        /** @var Profilo $profilo */
        $profilo = $this->organizationsModule->createModel('Profilo');
        $itemsMittente = $profilo->getUserNetworkWidgetColumns();

        $actionColumnsButtons = $profilo->getUserNetworkWidgetActionColumns(
            $model,
            $this->userId,
            $loggedUser,
            $loggedUserId
        );
        
        $defaultActionColumnsTemplate = $profilo->getUserNetworkWidgetActionColumnsTemplate();

        if ($this->adminModule->roleAndAreaOnOrganizations) {
            $defaultActionColumnsTemplate .= '{relationAttributeManage}';
            $itemsMittente[] = [
                'label' => Module::t('amosorganizzazioni', '#user_profile_role_label'),
                'value' => function ($model) {
                    /** @var ProfiloUserMm $model */
                    $str = '-';
                    if (!is_null($model->userProfileRole)) {
                        $str = $model->userProfileRole->name;
                    }
                    return $str;
                }
            ];
            $itemsMittente[] = [
                'label' => Module::t('amosorganizzazioni', '#user_profile_area_label'),
                'value' => function ($model) {
                    /** @var ProfiloUserMm $model */
                    $str = '-';
                    if (!is_null($model->userProfileArea)) {
                        $str = $model->userProfileArea->name;
                    }
                    return $str;
                }
            ];

          

            $actionColumnsButtons['relationAttributeManage'] = function ($url, $model) {
                /** @var ProfiloUserMm $model */
                $url = Yii::$app->urlManager->createUrl($createUrlParamsRole = [
                    '/' . Module::getModuleName() . '/profilo/change-user-role-area',
                    'profiloId' => $model->profilo_id,
                    'userId' => $this->userId
                ]);

                $modalId = 'change-organizzazioni-user-role-area-modal-' . $model->profilo_id;
                $selectRoleId = 'profilo_user_mm-role-' . $model->profilo_id;
                $selectAreaId = 'profilo_user_mm-area-' . $model->profilo_id;

                Modal::begin([
                    'header' => Module::t('amosorganizzazioni', 'Manage role and area'),
                    'id' => $modalId,
                ]);

                echo Html::tag(
                    'div',
                    Html::label('Ruolo', 'user_profile_role_id')
                        . Select::widget([
                            'auto_fill' => true,
                            'hideSearch' => true,
                            'theme' => 'bootstrap',
                            'data' => ArrayHelper::map(
                                $this->getUserProfileRoles($model),
                                'id',
                                'name'
                            ),
                            'model' => $model,
                            'attribute' => 'user_profile_role_id',
                            'options' => [
                                'prompt' => Module::t('amosorganizzazioni', '#choose...'),
                                'disabled' => false,
                                'id' => $selectRoleId
                            ],
                            'pluginOptions' => [
                                'allowClear' => false,
                            ]
                        ]),
                    ['class' => 'm-15-0']
                );

                echo Html::tag(
                    'div',
                    Html::label('Area', 'user_profile_area_id')
                        . Select::widget([
                            'auto_fill' => true,
                            'hideSearch' => true,
                            'theme' => 'bootstrap',
                            'data' => ArrayHelper::map(
                                $this->getUserProfileAreas($model),
                                'id',
                                'name'
                            ),
                            'model' => $model,
                            'attribute' => 'user_profile_area_id',
                            'options' => [
                                'prompt' => Module::t('amosorganizzazioni', '#choose...'),
                            'disabled' => false,
                            'id' => $selectAreaId
                        ],
                        'pluginOptions' => [
                            'allowClear' => false,
                        ]
                    ]), ['class' => 'm-15-0']);

                echo Html::tag('div',
                    Html::a(Module::t('amosorganizzazioni', '#cancel'),
                        null,
                        ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
                    . Html::a(Module::t('amosorganizzazioni', 'Save'),
                        null,
                        [
                            'class' => 'btn btn-primary',
                            'onclick' => "
                                $.ajax({
                                    url : '$url',
                                    type: 'POST',
                                    async: true,
                                    data: {
                                        user_profile_role: $('#$selectRoleId').val(),
                                        user_profile_area: $('#$selectAreaId').val()
                                    },
                                    success: function(response) {
                                       $('#$modalId').modal('hide');
                                       $('#reset-search-btn-$this->gridId').click();
                                   }
                                });
                            return false;"
                        ]),
                    ['class' => 'pull-right m-15-0']
                );
                Modal::end();

                $btn = Html::a(
                    Module::t('amosorganizzazioni', '#change_role_area'),
                    null, [
                    'class' => 'btn btn-tools-secondary btn-tools-secondary-text',
                    'title' => Module::t('amosorganizzazioni', '#change_role_area'),
                    'data-toggle' => 'modal',
                    'data-target' => '#' . $modalId,
                    'onclick' => 'checkSelect2Init("'
                        . $modalId
                        . '", "'
                        . $selectRoleId
                        . '");checkSelect2Init("'
                        . $modalId
                        . '", "'
                        . $selectAreaId
                        . '");'
                ]);

                return $btn;
            };
        }

        if ($this->organizationsModule->enableConfirmUsersJoinRequests) {
            $itemsMittente[] = [
                'label' => Module::t('amosorganizzazioni', '#profilo_user_mm_status_label'),
                'value' => function ($model) {
                    /** @var ProfiloUserMm $model */
                    $str = Module::t('amosorganizzazioni', $model->status);
                    return $str;
                }
            ];
        }

        $defaultActionColumnsTemplate .= '{joinOrganizzation}{deleteRelation}';

        $widget = M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'forceListRender' => true,
            'targetUrlParams' => [
                'viewM2MWidgetGenericSearch' => true
            ],
            'gridId' => $gridId,
            'firstGridSearch' => $this->organizationsModule->userNetworkWidgetSearchOrganization,
            'itemsSenderPageSize' => 10,
            'pageParam' => 'page-organizations',
            'disableCreateButton' => true,
            'disableAssociaButton' => $this->organizationsModule->disableAssociaButton,
            'createAssociaButtonsEnabled' => $this->isUpdate,
            'btnAssociaLabel' => Module::t('amosorganizzazioni', '#add_new_organization'),
            'actionColumnsTemplate' => $this->isUpdate ? $defaultActionColumnsTemplate : '',
            'deleteRelationTargetIdField' => 'user_id',
            'targetUrl' => '/'
                . Module::getModuleName()
                . '/profilo/associate-organization-m2m',
            'createNewTargetUrl' => '/'
                . AmosAdmin::getModuleName()
                . '/user-profile/create',
            'moduleClassName' => Module::className(),
            'targetUrlController' => 'organizzazioni',
            'postName' => 'User',
            'postKey' => 'user',
            'permissions' => [
                'add' => 'ASSOCIATE_ORGANIZZAZIONI_TO_USER',
                'manageAttributes' => 'ASSOCIATE_ORGANIZZAZIONI_TO_USER'
            ],
            'actionColumnsButtons' => $actionColumnsButtons,
            'itemsMittente' => $itemsMittente,
        ]);

        return "<div id='"
            . $gridId
            . "' data-pjax-container='"
            . $gridId
            . "-pjax' data-pjax-timeout=\"1000\">"
            . "<h3>"
            . Module::tHtml('amosorganizzazioni', '#user_network_widget_organization')
            . "</h3>"
            . $widget
            . "</div>";
    }

    /**
     * @param string $searchPostName
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    private function getModelDataQuery($searchPostName)
    {
        /** @var Profilo $profilo */
        $profilo = $this->organizationsModule->createModel('Profilo');
        /** @var ProfiloUserMm $profiloUserMm */
        $profiloUserMm = $this->organizationsModule->createModel('ProfiloUserMm');
        /** @var ActiveQuery $query */
        $query = $profiloUserMm::find();
        $query->innerJoinWith('profilo');
        $query->andWhere([
            $profiloUserMm::tableName() . '.user_id' => $this->userId
        ]);
        
        if ($this->organizationsModule->enableWorkflow) {
            $query->andWhere([
                $profilo::tableName() . '.status' => $profilo->getValidatedStatus()
            ]);
        }

        $searchName = Yii::$app->request->post($searchPostName);
        if (!is_null($searchName) && !empty($searchName)) {
            $query->andFilterWhere(['like', $profilo::tableName() . '.name', $searchName]);
        }
        return $query;
    }

    /**
     * @param ProfiloUserMm $profiloUserMm
     * @return UserProfileArea[]
     * @throws \yii\base\InvalidConfigException
     */
    private function getUserProfileAreas($profiloUserMm)
    {
        /** @var ActiveQuery $query */
        $query = UserProfileArea::find();
        if (
            $this->adminModule->roleAndAreaOnOrganizations
            && $this->adminModule->roleAndAreaFromOrganizationsWithTypeCat
        ) {
            $query->andWhere([
                'type_cat' => [
                    UserProfileArea::TYPE_CAT_GENERIC,
                    $profiloUserMm->profilo->profilo_enti_type_id
                ]
            ]);
        }

        $query->orderBy(['order' => SORT_ASC]);

        return $query->all();
    }

    /**
     * @param ProfiloUserMm $profiloUserMm
     * @return UserProfileArea[]
     * @throws \yii\base\InvalidConfigException
     */
    private function getUserProfileRoles($profiloUserMm)
    {
        /** @var ActiveQuery $query */
        $query = UserProfileRole::find();
        if ($this->adminModule->roleAndAreaOnOrganizations && $this->adminModule->roleAndAreaFromOrganizationsWithTypeCat) {
            $query->andWhere(['type_cat' => [UserProfileRole::TYPE_CAT_GENERIC, $profiloUserMm->profilo->profilo_enti_type_id]]);
        }
        $query->orderBy(['order' => SORT_ASC]);
        $areas = $query->all();
        return $areas;
    }
}
