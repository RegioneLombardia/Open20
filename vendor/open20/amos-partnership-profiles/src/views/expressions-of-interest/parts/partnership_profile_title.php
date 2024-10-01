<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\expressions-of-interest\parts
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $partnershipProfile
 */

?>

<div class="post-info col-xs-12 m-t-15 m-b-15">
    <div class="container-sidebar">
        <h4 class="title m-l-10">
            <?= Module::tHtml('amospartnershipprofiles', 'Partnership Profile') ?>
        </h4>
        <hr>
        <div class="col-sm-6">
            <div>
                <strong><?= $model->partnershipProfile->getAttributeLabel('title') ?>:</strong>
                <?= $model->partnershipProfile->title ?>
            </div>
            <div>
                <strong><?= Module::tHtml('amospartnershipprofiles', 'Creator') ?>:</strong>
                <?= $model->partnershipProfile->createdUserProfile->nomeCognome ?>
            </div>
            <div>
                <strong><?= Module::tHtml('amospartnershipprofiles', 'Facilitator') ?>:</strong>
                <?= (!is_null($model->partnershipProfile->partnershipProfileFacilitator) ? $model->partnershipProfile->partnershipProfileFacilitator->nomeCognome : '--') ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div>
                <strong><?= Module::t('amospartnershipprofiles',$model->partnershipProfile->getAttributeLabel('status')) ?>:</strong>
                <?= Module::t('amospartnershipprofiles',$model->partnershipProfile->getWorkflowStatus()->getLabel() )?>
            </div>
            <div>
                <strong><?= $model->partnershipProfile->getAttributeLabel('partnership_profile_date') ?>:</strong>
                <?= \Yii::$app->getFormatter()->asDate($model->partnershipProfile->partnership_profile_date) ?>
            </div>
            <div>
                <strong><?= Module::t('amospartnershipprofiles', 'Calculated Expiry Date') ?>:</strong>
                <?= PartnershipProfilesUtility::calcExpiryDateStr($model->partnershipProfile, true) ?>
            </div>
        </div>
    </div>
</div>
