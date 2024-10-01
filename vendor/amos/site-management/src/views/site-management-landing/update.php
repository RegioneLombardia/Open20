<?php

use yii\helpers\Html;
use amos\sitemanagement\Module;
/**
* @var yii\web\View $this
* @var amos\sitemanagement\models\SiteManagementLanding $model
*/

$this->title = Module::t('amossitemanagement', 'Aggiorna Landing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Site Management Landing'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'Aggiorna');
?>
<div class="site-management-landing-update">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
