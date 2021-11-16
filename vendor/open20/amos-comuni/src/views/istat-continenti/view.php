<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use open20\amos\comuni\AmosComuni;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatContinenti $model
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => 'Istat Continenti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="istat-continenti-view col-xs-12">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
        ],
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(AmosComuni::t('amoscomuni', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
