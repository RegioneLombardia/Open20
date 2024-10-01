<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatUnioneDeiComuni $model
 */

$this->title = 'Aggiorna Istat Unione Dei Comuni';
$this->params['breadcrumbs'][] = ['label' => 'Istat Unione Dei Comuni', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aggiorna';
?>
<div class="istat-unione-dei-comuni-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
