<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\IstatNazioni $model
 */

$this->title = 'Aggiorna Istat Nazioni';
$this->params['breadcrumbs'][] = ['label' => 'Istat Nazioni', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Aggiorna';
?>
<div class="istat-nazioni-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
