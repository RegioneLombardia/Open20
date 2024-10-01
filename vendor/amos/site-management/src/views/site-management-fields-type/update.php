<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementFieldsType $model
 */

$this->title = Module::t('amossitemanagement', 'Update fields');
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Fields Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('amossitemanagement', 'Aggiorna');
?>
<div class="site-management-fields-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
