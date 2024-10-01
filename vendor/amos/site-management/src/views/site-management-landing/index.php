<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var amos\sitemanagement\models\search\SiteManagementLandingSearch $model
 */

$this->title = Yii::t('amossitemanagement', 'Landing');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-landing-index">
    <?php // echo $this->render('_search', ['model' => $model]); ?>

    <p>
        <?php /* echo         Html::a(Yii::t('cruds', 'Nuovo {modelClass}', [
    'modelClass' => 'Site Management Landing',
])        , ['create'], ['class' => 'btn btn-amministration-primary'])*/ ?>
    </p>

    <?php echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'name',
                'description',
                'view_path',
                'layout_path',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],

    ]); ?>

</div>
