<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementSection $model
 */

$this->title = Module::t('amossitemanagement', 'Aggiorna {modelClass}', [
    'modelClass' => 'Site Management Section',
]);
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Section'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('amossitemanagement', 'Aggiorna');
?>
<div class="site-management-section-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
