<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\views\site-management-container
 * @category   CategoryName
 */

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementContainer $model
 */

$this->title = Module::t('amossitemanagement', 'Container') . " '".$model->title."'";

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-container-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProviderElements' => $dataProviderElements
    ]) ?>
</div>
