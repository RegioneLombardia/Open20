<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\Module;

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 */

$this->title = Module::t('amospartnershipprofiles', 'Create');
$this->params['breadcrumbs'][] = ['label' => Module::t('amospartnershipprofiles', 'Partnership Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Yii::$app->controller->id ?>-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => null,
        'dataField' => null,
        'dataEntity' => null
    ]) ?>
</div>
