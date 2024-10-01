<?php

use open20\amos\core\helpers\Html;
use amos\sitemanagement\Module;

/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\SiteManagementLandingPubblication $model
*/

$this->title = Module::t('amossitemanagement', 'Create landing pubblication');

$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Site Management Landing Pubblication'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-management-landing-pubblication-create">
    <?= $this->render('_form', [
        'model' => $model,
        'routes' => $routes

    ]) ?>

</div>
