<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\metadata
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var amos\sitemanagement\models\search\MetadataSearch $model
 * @var string $currentView
 */

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="<?= Yii::$app->controller->id ?>-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => $model->getGridViewColumns()
        ]
    ]); ?>
</div>
