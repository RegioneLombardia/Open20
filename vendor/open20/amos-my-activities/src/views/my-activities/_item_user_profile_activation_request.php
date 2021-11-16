<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\views\my-activities
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\myactivities\AmosMyActivities;

/** @var $model \open20\amos\myactivities\basic\WaitingContacts */

?>
<div class="wrap-activity">
    <div class="col-md-1 col-xs-2 icon-plugin">
        <?= AmosIcons::show('users', [], 'dash') ?>
    </div>
    <div class="col-md-3 col-xs-5 wrap-user">
        <?= UserCardWidget::widget(['model' => $model]) ?>
        <span class="user"><?= $model->getNomeCognome() ?></span>
        <br>
        <?= AmosAdmin::t('amosadmin', $userProfile->workflowStatus->label) ?>
    </div>
    <div class="col-md-5 col-xs-5 wrap-report">
        <div class="col-lg-12 col-xs-12">
            <strong><?= AmosMyActivities::t('amosmyactivities', 'User reactivation request'); ?></strong>
        </div>
        <div class="col-lg-12 col-xs-12">
            <?= Yii::$app->formatter->asDatetime($model->userProfileReactivationRequest->updated_at) ?>
        </div>
        <div class="col-lg-12 col-xs-12">
            <?= AmosMyActivities::t('amosmyactivities', '#message') . ': ' ?><?= $model->userProfileReactivationRequest->message ?>
        </div>
        <div class="col-lg-12 col-xs-12">
            <?php /** @var \open20\amos\core\interfaces\ViewModelInterface $model */ ?>
            <?= Html::a(AmosIcons::show('search', [], 'dash') . ' <span>' . AmosMyActivities::t('amosmyactivities',
                    'View card') . '</span>', $model->getFullViewUrl()
//            Yii::$app->urlManager->createUrl([
//                '/community/community/view',
//                'id' => $model->id
//            ])
            ) ?>
        </div>
    </div>
    <div class="col-md-3 col-xs-12 wrap-action">
        <?= ModalUtility::addConfirmRejectWithModal([
            'modalId' => 'validate-user-profile-modal-id-' . $model->id,
            'modalDescriptionText' => AmosMyActivities::t('amosmyactivities', '#ACTIVATE_USER_PROFILE'),
            'btnText' => AmosIcons::show('check') . ' ' . AmosMyActivities::t('amosmyactivities', 'Activate'),
            'btnLink' => Yii::$app->urlManager->createUrl([
                '/admin/user-profile/reactivate-account',
                'id' => $model->id
            ]),
            'btnOptions' => [
                'class' => 'btn btn-primary'
            ]
        ]); ?>
        <?php echo ModalUtility::addConfirmRejectWithModal([
            'modalId' => 'reject-user-profile-modal-id-' . $model->id,
            'modalDescriptionText' => AmosMyActivities::t('amosmyactivities', '#REJECT_USER_PROFILE_MODAL_TEXT'),
            'btnText' => AmosIcons::show('close') . ' ' . AmosMyActivities::t('amosmyactivities', 'Reject'),
            'btnLink' => Yii::$app->urlManager->createUrl([
                '/admin/user-profile/reject-reactivation-request',
                'id' => $model->id
            ]),
            'btnOptions' => [
                'class' => 'btn btn-secondary'
            ]
        ]); ?>
    </div>
</div>
<hr>
