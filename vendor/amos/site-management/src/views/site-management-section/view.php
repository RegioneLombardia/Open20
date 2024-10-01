<?php

use amos\sitemanagement\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementSection $model
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Section'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-section-view col-xs-12">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description',
            [
                'attribute' => 'created_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            [
                'attribute' => 'deleted_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            'created_by',
            'updated_by',
            'deleted_by',
        ],
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>
    </div>

</div>
