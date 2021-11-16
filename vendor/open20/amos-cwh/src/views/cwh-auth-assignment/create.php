<?php
use open20\amos\cwh\AmosCwh;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhConfig $model
 * @var \yii\rbac\Permission[] $authItems
 */

$this->title = AmosCwh::t('amoscwh', 'Crea nuovo dominio');
$this->params['breadcrumbs'][] = ['label' => AmosCwh::t('amoscwh', 'Cwh Domini'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cwh-config-create col-xs-12">
    <?= $this->render('_form', [
        'model' => $model,
        'authItems' => $authItems,
    ]) ?>

</div>
