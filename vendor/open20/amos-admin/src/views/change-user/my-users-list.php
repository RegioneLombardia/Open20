<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\change-user
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $currentView
 */

/** @var \open20\amos\admin\controllers\ChangeUserController $appController */
$appController = Yii::$app->controller;

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

ModuleAdminAsset::register($this);

$dataProviderViewWidgetConf = [
    'dataProvider' => $dataProvider,
    'currentView' => $currentView,
    'iconView' => [
        'itemView' => '_icon',
        'itemOptions' => [
            'class' => 'col-xs-12 col-sm-6 col-md-4 col-lg-4',
            'aria-selected' => 'false',
            'role' => 'option'
        ],
        'summary' => false,
    ],
];

?>

<div class="change-user-index">
    <?= DataProviderView::widget($dataProviderViewWidgetConf); ?>
</div>
