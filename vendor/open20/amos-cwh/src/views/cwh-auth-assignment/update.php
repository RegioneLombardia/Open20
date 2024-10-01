<?php
use open20\amos\cwh\AmosCwh;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhConfig $model
 * @var \yii\rbac\Permission[] $authItems
 */

$this->title = AmosCwh::t('amoscwh', 'Aggiorna {modelClass}: ', [
        'modelClass' => 'Cwh Domini',
    ]) . ' ' . $model;
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Cwh Domini'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = AmosCwh::t('amoscwh', 'Aggiorna');
?>
<div class="cwh-config-update">

    <?= $this->render('_form', [
        'model' => $model,
        'authItems' => $authItems,
    ]) ?>

</div>
