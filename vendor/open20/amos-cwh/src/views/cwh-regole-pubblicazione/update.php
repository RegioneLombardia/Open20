<?php
use open20\amos\cwh\AmosCwh;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhRegolePubblicazione $model
 */

$this->title = AmosCwh::t('amoscwh', 'Aggiorna {modelClass}: ', [
        'modelClass' => 'Regole Pubblicazione',
    ]) . ' ' . $model;
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Regole Pubblicazione'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = AmosCwh::t('amoscwh', 'Aggiorna');
?>
<div class="cwh-regole-pubblicazione-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
