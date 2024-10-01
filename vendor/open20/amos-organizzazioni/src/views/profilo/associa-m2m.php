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
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\controllers\ProfiloController;
use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var \open20\amos\organizzazioni\models\Profilo $model
 */

$this->title = Module::t('amosorganizzazioni', '#associa_m2m_page_title');
$this->params['breadcrumbs'][] = $this->title;

/** @var ProfiloController $appController */
$appController = Yii::$app->controller;

/** @var UserProfile $emptyUserProfile */
$emptyUserProfile = AmosAdmin::instance()->createModel('UserProfile');

$userQuery = $model->getProfiloUsers();
$associaM2mQuery = $appController->getAssociaM2mQuery($model);

$organizzazioniModule = Yii::$app->getModule(Module::getModuleName());
$profiloUserMmModel = $organizzazioniModule->createModel('ProfiloUserMm');
$profiloUsers = $profiloUserMmModel::find()->select('user_id')->All();
$idToExclude = [];
foreach ($profiloUsers as $pu){
	$idToExclude[] = $pu->user_id;
}
if (!empty(\Yii::$app->request->get('role_name'))) {
    $userQuery->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
        ->andWhere(['auth_assignment.item_name' => \Yii::$app->request->get('role_name')]);
    $associaM2mQuery->leftJoin('auth_assignment', 'auth_assignment.user_id = user.id')
        ->andWhere(['auth_assignment.item_name' => \Yii::$app->request->get('role_name')]);
}
$associaM2mQuery->andWhere(['not in','user_id',$idToExclude]);

?>

<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $userQuery,
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
    ],
    'modelTargetSearch' => [
        'class' => User::className(),
        'query' => $associaM2mQuery,
    ],
    'gridId' => 'organizations-employees-grid',
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
//    'isModal' => true, // TODO da ripristinare. Da fare fix per reload sezione impiegati in form che non ricarica i pulsanti quando post associazione dipendente.
    'relationAttributesArray' => ['status', 'role'],
    'targetUrlController' => 'profilo',
    'moduleClassName' => Module::className(),
    'postName' => 'Profilo',
    'postKey' => 'user',
    'redirectCancelButton' => \Yii::$app->request->get('redirectUrlAfterAssociate'),
    'targetColumnsToView' => [
        'userImage' => [
            'label' => $emptyUserProfile->getAttributeLabel('userProfileImage'),
            'headerOptions' => [
                'id' => Module::t('amosorganizzazioni', 'User image'),
            ],
            'contentOptions' => [
                'headers' => Module::t('amosorganizzazioni', 'User image'),
            ],
            'format' => 'raw',
            'value' => function ($model) {
                /** @var \open20\amos\core\user\User $model */
                return UserCardWidget::widget(['model' => $model->userProfile, 'containerAdditionalClass' => 'nom']);
            }
        ],
        'name' => [
            'attribute' => 'profile.surnameName',
            'label' => Module::t('amosorganizzazioni', 'Name'),
            'headerOptions' => [
                'id' => Module::t('amosorganizzazioni', 'Name'),
            ],
            'contentOptions' => [
                'headers' => Module::t('amosorganizzazioni', 'Name'),
            ]
        ],
    ],
]);
