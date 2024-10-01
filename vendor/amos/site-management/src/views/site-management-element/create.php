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

$this->title = Module::t('amossitemanagement', 'Create element for container');
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Element'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-element-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
