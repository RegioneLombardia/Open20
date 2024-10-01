<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\views\DataProviderView;
use open20\amos\organizzazioni\controllers\ProfiloController;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\widgets\OrganizationsMembersWidget;
use kartik\alert\Alert;
use yii\data\ActiveDataProvider;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\organizzazioni\models\Profilo $model
 * @var bool $isUpdate
 */

/** @var ProfiloController $appController */
$appController = Yii::$app->controller;

/** @var Module $organizzazioniModule */
$organizzazioniModule = Yii::$app->getModule(Module::getModuleName());

/** @var UserProfile $emptyUserProfile */
$emptyUserProfile = AmosAdmin::instance()->createModel('UserProfile');
$emptyUser = new User();
$isUpdate = (isset($isUpdate) ? $isUpdate : false);
?>

<?php if ($model->isNewRecord): ?>
    <?= Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'body' => Module::t('amosorganizzazioni', '#alert_invite_employees'),
        'closeButton' => false
    ]); ?>
<?php else: ?>
    <?php if (!$isUpdate): ?>
        <?php
        $columns = [
            [
                'label' => $emptyUserProfile->getAttributeLabel('userProfileImage'),
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return UserCardWidget::widget(['model' => $model->user->userProfile]);
                }
            ],
            [
                'attribute' => 'user.userProfile.nameSurname',
                'label' => Module::t('amosadmin', 'Nome Cognome'),
                'value' => function ($model) {
                    /** @var \open20\amos\organizzazioni\models\ProfiloUserMm $model */
                    return $model->user->userProfile->nomeCognome;
                }
            ],
            [
                'attribute' => 'user.email',
                'label' => $emptyUser->getAttributeLabel('email')
            ],
        ];

        if ($organizzazioniModule->viewStatusEmployees) {
            $statusLabel = Module::t('amosorganizzazioni', '#profilo_user_mm_status_label');
            $columns['status'] = [
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
        }
        
        if ($organizzazioniModule->viewRoleEmployees) {
            $roleLabel = Module::t('amosorganizzazioni', '#profilo_user_mm_role_label');
            $columns['role'] = [
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
        ?>
        
        <div class="col-xs-12">
        	<?php
        	if($organizzazioniModule->employeesListView == "icon"){
        	    $iconView = '@vendor/open20/amos-organizzazioni/src/views/profilo/icon_contacts';
        	    $icon = [
        	        'name' => 'icon',
        	        'label' => BaseAmosModule::t('amoscore', '{iconaElenco}' . Html::tag('p', BaseAmosModule::t('amoscore', 'Icone')), [
        	            'iconaElenco' => AmosIcons::show('grid')
        	        ]),
        	        'url' => '?currentView=icon'
        	    ];
        	    echo DataProviderView::widget([
        	        'dataProvider' => new ActiveDataProvider([
        	            'query' => $appController->getOrganizationEmployeesQuery($model, false),
        	            'pagination' => [
        	                'pageSize' => 6,
        	            ],
        	        ]),
        	        'currentView' => $icon,
        	        'iconView' => [
        	            'itemView' => $iconView
        	        ],
        	    ]);
        	}else{
        	    echo AmosGridView::widget([
        	        'dataProvider' => new ActiveDataProvider([
        	            'query' => $appController->getOrganizationEmployeesQuery($model, false)
        	        ]),
        	        'columns' => $columns
        	    ]);
        	}
            ?>
        </div>
    <?php else: ?>
        <?php
        $widgetConf = [
            'model' => $model,
//            'enableModal' => (isset($enableModal) ? $enableModal : true), // TODO da ripristinare. Da fare fix per reload sezione impiegati in form che non ricarica i pulsanti quando post associazione dipendente.
            'targetUrlParams' => (isset($targetUrlParams) ? $targetUrlParams : ['viewM2MWidgetGenericSearch' => true])
        ];
        if (isset($showRoles)) {
            $widgetConf['showRoles'] = $showRoles;
        }
        if (isset($showAdditionalAssociateButton)) {
            $widgetConf['showAdditionalAssociateButton'] = $showAdditionalAssociateButton;
        }
        if (isset($additionalColumns)) {
            $widgetConf['additionalColumns'] = $additionalColumns;
        }
        if (isset($viewEmail)) {
            $widgetConf['viewEmail'] = $viewEmail;
        }
        if (isset($checkManagerRole)) {
            $widgetConf['checkManagerRole'] = $checkManagerRole;
        }
        if (isset($addPermission)) {
            $widgetConf['addPermission'] = $addPermission;
        }
        if (isset($manageAttributesPermission)) {
            $widgetConf['manageAttributesPermission'] = $manageAttributesPermission;
        }
        if (isset($forceActionColumns)) {
            $widgetConf['forceActionColumns'] = $forceActionColumns;
        }
        if (isset($actionColumnsTemplate)) {
            $widgetConf['actionColumnsTemplate'] = $actionColumnsTemplate;
        }
        if (isset($viewM2MWidgetGenericSearch)) {
            $widgetConf['viewM2MWidgetGenericSearch'] = $viewM2MWidgetGenericSearch;
        }
        if (isset($gridId)) {
            $widgetConf['gridId'] = $gridId;
        }
        if (isset($organizationManagerRoleName)) {
            $widgetConf['organizationManagerRoleName'] = $organizationManagerRoleName;
        }
        ?>
        <?= OrganizationsMembersWidget::widget($widgetConf); ?>
    <?php endif; ?>
<?php endif; ?>
