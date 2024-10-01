<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementFieldsType $model
 */

$this->title = Module::t('amossitemanagement', 'Create {modelClass}', [
    'modelClass' => 'Site Management Fields Type',
]);
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Fields Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-fields-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
