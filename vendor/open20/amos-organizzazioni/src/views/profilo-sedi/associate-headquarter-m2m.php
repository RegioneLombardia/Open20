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
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\organizzazioni\controllers\ProfiloSediController;
use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var \open20\amos\organizzazioni\models\ProfiloSedi $model
 */

$this->title = Module::t('amosorganizzazioni', '#add_headquarter');
$this->params['breadcrumbs'][] = $this->title;

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

/** @var Module $organizzazioniModule */
$organizzazioniModule = Module::instance();

/** @var ProfiloSediController $appController */
$appController = Yii::$app->controller;

$userProfileId = Yii::$app->request->get("id");

/** @var UserProfile $userProfileModel */
$userProfileModel = $adminModule->createModel('UserProfile');
$userProfile = $userProfileModel::findOne(['id' => $userProfileId]);
$userId = $userProfile->user_id;

$query = $appController->getAssociateHeadquarterM2mQuery($userId);

$closeLabel = Module::t('amosorganizzazioni', '#close');

?>
<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $query,
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
    ],
    'modelTargetSearch' => [
        'class' => $organizzazioniModule->model('ProfiloSedi'),
        'query' => $query,
    ],
    'targetFooterButtons' => Html::a($closeLabel, Yii::$app->urlManager->createUrl([
        '/organizzazioni/profilo-sedi/annulla-m2m',
        'id' => $userId
    ]), ['class' => 'btn btn-secondary', 'title' => $closeLabel]),
    'renderTargetCheckbox' => false,
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
//    'relationAttributesArray' => ['status', 'role'],
    'targetUrlController' => 'profilo-sedi',
    'targetActionColumnsTemplate' => '{joinOrganization}',
    'moduleClassName' => Module::className(),
    'postName' => 'Organization',
    'postKey' => 'organization',
    'targetColumnsToView' => $appController->getAssociateHeadquarterM2mTargetColumns($userId)
]);
?>
