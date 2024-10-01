<?php

use yii\helpers\Html;
use open20\amos\comuni\AmosComuni;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatComuni $model
 */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Comuni', 'url' => ['/comuni/dashboard/index']];
$this->params['breadcrumbs'][] = ['label' => 'Elenco comuni', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosComuni::t('amoscomuni', 'Aggiorna');
?>
<div class="istat-comuni-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
