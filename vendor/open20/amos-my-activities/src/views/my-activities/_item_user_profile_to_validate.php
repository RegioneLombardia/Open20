<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\views\my-activities
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\myactivities\AmosMyActivities;

/** @var $model \open20\amos\myactivities\basic\WaitingContacts */

?>
<div class="wrap-activity">
    <div class="col-md-1 col-xs-2 icon-plugin">
        <?= AmosIcons::show('users', [], 'dash') ?>
    </div>
    <?= \open20\amos\myactivities\widgets\UserRequestValidation::widget([
        'model' => $model,
        'labelKey' => AmosMyActivities::t('amosmyactivities', 'User validation request'),
    ]) ?>
    <div class="col-md-3 col-xs-12 wrap-action">
        <?= ModalUtility::addConfirmRejectWithModal([
            'modalId' => 'validate-user-profile-modal-id-' . $model->id,
            'modalDescriptionText' => AmosMyActivities::t('amosmyactivities', '#VALIDATE_USER_PROFILE_MODAL_TEXT'),
            'btnText' => AmosIcons::show('check') . ' ' . AmosMyActivities::t('amosmyactivities', 'Validate'),
            'btnLink' => Yii::$app->urlManager->createUrl([
                '/admin/user-profile/validate-user-profile',
                'id' => $model->id
            ]),
            'btnOptions' => [
                'class' => 'btn btn-primary'
            ]
        ]); ?>
        <?= ModalUtility::addConfirmRejectWithModal([
            'modalId' => 'reject-user-profile-modal-id-' . $model->id,
            'modalDescriptionText' => AmosMyActivities::t('amosmyactivities', '#REJECT_USER_PROFILE_MODAL_TEXT'),
            'btnText' => AmosIcons::show('close') . ' ' . AmosMyActivities::t('amosmyactivities', 'Reject'),
            'btnLink' => Yii::$app->urlManager->createUrl([
                '/admin/user-profile/reject-user-profile',
                'id' => $model->id
            ]),
            'btnOptions' => [
                'class' => 'btn btn-secondary'
            ]
        ]); ?>
    </div>
</div>
<hr>
