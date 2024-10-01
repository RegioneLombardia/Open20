<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementTemplate $model
 */

$this->title = Module::t('amossitemanagement', 'Create {modelClass}', [
    'modelClass' => 'Site Management Template',
]);
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Template'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-template-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
