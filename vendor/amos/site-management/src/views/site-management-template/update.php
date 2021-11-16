<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementTemplate $model
 */

$this->title = Module::t('amossitemanagement', 'Aggiorna Template') . " '{$model->name}'";
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Template'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('amossitemanagement', 'Aggiorna');
?>
<div class="site-management-template-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
