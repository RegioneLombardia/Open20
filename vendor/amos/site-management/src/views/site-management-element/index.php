<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\site-management-element
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var amos\sitemanagement\models\search\SiteManagementElementSearch $model
 */

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="<?= Yii::$app->controller->id ?>-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'siteManagementTemplate.name',
                'title',
                'description:ntext',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ]
            ]
        ]
    ]); ?>
</div>
