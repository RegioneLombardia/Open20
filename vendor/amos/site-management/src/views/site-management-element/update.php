<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\site-management-element
 * @category   CategoryName
 */

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementElement $model
 */

$this->title = Module::t('amossitemanagement', 'Update element');
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Element'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('amossitemanagement', 'Aggiorna');
?>
<div class="site-management-element-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dynamicModel' => $dynamicModel,
        'attributesTypes' => $attributesTypes,
        'modelFieldImages' => $modelFieldImages


    ]) ?>
</div>
