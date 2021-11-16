<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManageContElemPubblication $model
 */

$this->title = Module::t('amossitemanagement', 'Aggiorna {modelClass}', [
    'modelClass' => 'Site Manage Cont Elem Pubblication',
]);
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Manage Cont Elem Pubblication'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('amossitemanagement', 'Aggiorna');
?>
<div class="site-manage-cont-elem-pubblication-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
