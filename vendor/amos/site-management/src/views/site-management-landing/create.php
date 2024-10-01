<?php

use open20\amos\core\helpers\Html;
use amos\sitemanagement\Module;

/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\SiteManagementLanding $model
*/

$this->title = Module::t('amossitemanagement', 'Create Landing');

$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-landing-create">
    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
