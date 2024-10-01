<?php

use open20\amos\core\helpers\Html;
use amos\sitemanagement\Module;

/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\SiteManagementContainerElementMm $model
*/

$this->title = Yii::t('amossitemanagement', 'Add element to container') . ' "' . $modelContainer->title .'"';
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Management Container Element Mm'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-container-element-mm-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelContainer' => $modelContainer,
        'modelPubblication' => $modelPubblication,
        'elementSearch' => $elementSearch,
        'dataProviderElements' => $dataProviderElements
    ]) ?>

</div>
