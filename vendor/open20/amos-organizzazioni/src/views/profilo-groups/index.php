<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-groups
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\organizzazioni\models\search\ProfiloGroupsSearch $searchModel
 */

?>
<div class="<?= Yii::$app->controller->id ?>-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'name',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn'
                ]
            ]
        ]
    ]); ?>
</div>
