<?php
use open20\amos\cwh\AmosCwh;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhNodi $model
 */

$this->title = AmosCwh::t('amoscwh', 'Create {modelClass}', [
    'modelClass' => 'Cwh Nodi',
]);
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Cwh Nodi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cwh-nodi-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
