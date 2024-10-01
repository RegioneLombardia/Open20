<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;
use open20\amos\partnershipprofiles\controllers\PartnershipProfilesController;
use open20\amos\partnershipprofiles\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\partnershipprofiles\models\search\PartnershipProfilesSearch $model
 * @var string $currentView
 */

$this->title = Module::t('amospartnershipprofiles', 'Partnership Profiles');
$this->params['breadcrumbs'][] = $this->title;

/** @var PartnershipProfilesController $appController */
$appController = Yii::$app->controller;
$ownInterestPartnershipProfileIds = $appController->getOwnInterestPartnershipProfiles(true);

?>
<div class="<?= Yii::$app->controller->id ?>-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= $this->render('_order', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => $model->getGridViewColumns()
        ],
        'listView' => [
            'itemView' => '_item',
            'viewParams' => [
                'ownInterestPartnershipProfileIds' => $ownInterestPartnershipProfileIds
            ],
            'showItemToolbar' => false,
        ]
    ]); ?>
</div>
