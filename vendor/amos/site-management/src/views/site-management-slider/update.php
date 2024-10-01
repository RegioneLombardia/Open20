<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var \amos\sitemanagement\models\SiteManagementSlider $model
 * @var  $dataProviderSliderElem \yii\data\ActiveDataProvider
 */

$this->title = Yii::t('amossitemanagement', 'Slider');
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Slider'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Module::t('amossitemanagement', 'Aggiorna');
?>
<div class="site-management-slider-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProviderSliderElem' => $dataProviderSliderElem

    ]) ?>
</div>
