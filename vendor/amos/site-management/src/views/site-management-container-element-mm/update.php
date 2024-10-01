<?php

use yii\helpers\Html;
use amos\sitemanagement\Module;


/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\SiteManagementContainerElementMm $model
*/

$this->title = Yii::t('amossitemanagement', 'Update element of container') . ' "' . $modelContainer->title .'"';;
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Container Element Mm'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-container-element-mm-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelContainer' => $modelContainer,
        'modelPubblication' => $modelPubblication,
        'elementSearch' => $elementSearch,
        'dataProviderElements' => $dataProviderElements
    ]) ?>

</div>
