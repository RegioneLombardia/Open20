<?php

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementSection $model
 */

use amos\sitemanagement\Module;

$this->title = Module::t('amossitemanagement', 'Create {modelClass}', [
    'modelClass' => 'Site Management Section',
]);
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Section'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-section-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
