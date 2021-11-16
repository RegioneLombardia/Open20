<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use open20\amos\comuni\AmosComuni;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatNazioni $model
 */

$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => 'Istat Nazioni', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="istat-nazioni-view col-xs-12">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'nome_inglese',
            'area',
            'unione_europea',
            'istat_continenti_id',
        ],
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(AmosComuni::t('amoscomuni', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
