<?php

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhConfig $model
 */
use open20\amos\cwh\AmosCwh;

$this->title = AmosCwh::t('amoscwh', 'Update {modelClass}: ', [
        'modelClass' => 'Cwh Config',
    ]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Cwh Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosCwh::t('amoscwh', 'Update');
?>
<div class="cwh-config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
