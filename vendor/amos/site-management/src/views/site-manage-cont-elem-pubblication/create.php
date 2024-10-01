<?php

use amos\sitemanagement\Module;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManageContElemPubblication $model
 */

$this->title = Module::t('amossitemanagement', 'Create {modelClass}', [
    'modelClass' => 'Site Manage Cont Elem Pubblication',
]);
$this->params['breadcrumbs'][] = ['label' => Module::t('amossitemanagement', 'Site Manage Cont Elem Pubblication'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-manage-cont-elem-pubblication-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
