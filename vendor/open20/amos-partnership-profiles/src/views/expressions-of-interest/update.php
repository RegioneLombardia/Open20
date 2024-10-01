<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\expressions-of-interest
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\Module;

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\ExpressionsOfInterest $model
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $partnershipProfile
 */

$this->title = Module::t('amospartnershipprofiles', 'Update expression of interest');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="<?= Yii::$app->controller->id ?>-update">
    <?= $this->render('_form', [
        'model' => $model,
        'partnershipProfile' => $partnershipProfile,
    ]) ?>
</div>
