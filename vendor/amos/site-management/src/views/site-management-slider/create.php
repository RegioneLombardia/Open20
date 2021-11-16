<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var \amos\sitemanagement\models\SiteManagementSlider $model
 */

$this->title = Yii::t('amossitemanagement', 'Create slider');

$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Slider'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-slider-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
