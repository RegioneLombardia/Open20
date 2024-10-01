<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatContinenti $model
 */

$this->title = 'Aggiorna Istat Continenti';
$this->params['breadcrumbs'][] = ['label' => 'Istat Continenti', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aggiorna';
?>
<div class="istat-continenti-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
