<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\SiteManagementLandingPubblication $model
*/

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Site Management Landing Pubblication'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-landing-pubblication-view col-xs-12">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
                'id',
            'url:url',
            'landing_id',
            [
                'attribute'=>'start_date',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            [
                'attribute'=>'end_date',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            [
                'attribute'=>'start_time',
                'format'=>['time',(isset(Yii::$app->modules['datecontrol']['displaySettings']['time'])) ? Yii::$app->modules['datecontrol']['displaySettings']['time'] : 'H:i:s A'],               
            ],
            [
                'attribute'=>'end_time',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            [
                'attribute'=>'created_at',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            [
                'attribute'=>'updated_at',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            [
                'attribute'=>'deleted_at',
                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],                
            ],
            'created_by',
            'updated_by',
            'deleted_by',
    ],    
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
