<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\comuni\AmosComuni;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatProvince $model
 */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Comuni', 'url' => ['/comuni/dashboard/index']];
$this->params['breadcrumbs'][] = ['label' => 'Elenco province', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="istat-province-view col-xs-12">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'sigla',
            'capoluogo:statosino',
            'soppressa:statosino',
            'istatCittaMetropolitane.nome',
            'istatRegioni.nome',
        ],
    ]) ?>

    <div class="btnViewContainer pull-right">
        <?= Html::a(AmosComuni::t('amoscomuni', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
