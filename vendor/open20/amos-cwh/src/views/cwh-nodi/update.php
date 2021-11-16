<?php
use open20\amos\cwh\AmosCwh;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhNodi $model
 */

$this->title = AmosCwh::t('amoscwh', 'Update {modelClass}: ', [
        'modelClass' => 'Cwh Nodi',
    ]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Cwh Nodi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = AmosCwh::t('amoscwh', 'Update');
?>
<div class="cwh-nodi-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
