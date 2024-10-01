<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
* @var yii\web\View $this
* @var open20\amos\admin\models\TokenGroup $model
*/

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Token Group'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="token-group-view col-xs-12">

    <?= DetailView::widget([
    'model' => $model,    
    'attributes' => [
            'name',
            'string_code',
            'Description',
            'url_redirect:url',
            'target_class',
            'target_id',
            'consumable',
//            [
//                'attribute'=>'expire_date',
//                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
//            ],
//            [
//                'attribute'=>'created_at',
//                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
//            ],
//            [
//                'attribute'=>'updated_at',
//                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
//            ],
//            [
//                'attribute'=>'deleted_at',
//                'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
//            ],
            'created_by',
            'updated_by',
            'deleted_by',
    ],    
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
