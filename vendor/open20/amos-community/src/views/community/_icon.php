<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\core\helpers\Html;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\community\models\CommunityType;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\user\User;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\community\models\Community;
use open20\design\assets\ShimmerDesignAsset;
ShimmerDesignAsset::register($this);


/**
 * @var \open20\amos\community\models\Community $model
 */
$communityModule    = Yii::$app->getModule('community');
$fixedCommunityType = !is_null($communityModule->communityType);
$hideCommunityType = $communityModule->hideCommunityTypeInCommunityIconView;
$bypassWorkflow     = $communityModule->forceWorkflow($model);
$isGuest            = \Yii::$app->user->isGuest;
$isOpenCommunity = false;
$isClosedCommunity = false;
$isPrivateCommunity = false;
$isWaitingToSigned = false;

$urlRedirect        = null;
if (!$isGuest) {
    $loggedUserId  = Yii::$app->getUser()->getId();
    $userCommunity = CommunityUserMm::findOne(['community_id' => $model->id, 'user_id' => $loggedUserId]);
    $userProfile   = User::findOne($loggedUserId)->getProfile();
    if (!empty($communityModule) && $communityModule->enableAutoLinkLanding == true && !empty($model->redirect_url)) {
        $urlRedirect = $model->redirect_url;
    }

    $viewUrl = '/community/join/open-join?id=' . $model->id;
    $loggedUserId  = Yii::$app->getUser()->getId();
    if (!empty($loggedUserId)) {
        $userCommunity = CommunityUtil::getMemberCommunityLogged($model->id);
        $userProfile   = User::findOne($loggedUserId)->getProfile();
    }

    if (!empty($userProfile) && $userProfile->validato_almeno_una_volta && !is_null($userCommunity)) {
        if (in_array($userCommunity->status, [CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER, CommunityUserMm::STATUS_WAITING_OK_USER])) {
            $isWaitingToSigned = true;
        } else {
            $isSigned = true;
        }
    } else {
        $isSigned = false;
    }

    if ($model->community_type_id == CommunityType::COMMUNITY_TYPE_OPEN) {
        $isOpenCommunity = true;
    } else if ($model->community_type_id == CommunityType::COMMUNITY_TYPE_CLOSED) {
        $isClosedCommunity = true;
    } else if ($model->community_type_id == CommunityType::COMMUNITY_TYPE_PRIVATE) {
        $isPrivateCommunity = true;
    } else {
    }

    if (!$isOpenCommunity) {
        if ($isWaitingToSigned) {
            $viewUrl = '/community/community/view?id=' . $model->id;
        }
    }
} else {
    $viewUrl = '/community/community/view?id=' . $model->id;
}
?>

<div class="community-container p-3 d-flex flex-column justify-content-center align-items-center mb-5">
    <?php $buttonTransform = \yii\helpers\Html::a(AmosCommunity::t('amoscommunity', "Trasforma in community"), ['/community/community/transform-to-community-parent', 'id' => $model->id], [
        'title' => AmosCommunity::t('amoscommunity', "Trasforma in community"),
        'data-confirm' => AmosCommunity::t('amoscommunity', 'Sei sicuro di trasformare la sottocommunity in community?')
    ]) ?>
    <?php $buttonMove = \yii\helpers\Html::a(AmosCommunity::t('amoscommunity', "Sposta"), ['/community/community/move', 'id' => $model->id], [
        'title' => AmosCommunity::t('amoscommunity', "Sposta sotto un'altra community")
    ]) ?>
    <?php $additionalButtons = [];
    if (!$isGuest) {
        if (Yii::$app->getUser()->can('COMMUNITY_UPDATE', ['model' => $model])) {
            $additionalButtons[] = $buttonMove;
            if (!empty($model->parent_id)) {
                $additionalButtons[] = $buttonTransform;
            }
        }
    }

    $newsWidget = NewsWidget::widget([
        'model' => $model,
        'css_class' => 'badge badge-pill'
    ])
    ?>
    <div class="image-container position-relative">
        <div class="community-image ">
            <?php
            $url        = '/img/img_default.jpg';
            if (!is_null($model->communityLogo)) {
                $url = $model->communityLogo->getWebUrl('item_community', false, true);
            }
            $logo = Html::img(
                $url,
                [
                    'class' => 'img-responsive shimmer-image',
                    'alt' => $model->getAttributeLabel('communityLogo')
                ]
            );

            if (!$isGuest) {
                if (!empty($urlRedirect)) {
                    echo Html::a($logo . $newsWidget, $urlRedirect), ['title' => $model->name, 'class' => 'img-shimmer', 'target' => '_blank'];
                } else {
                    echo Html::a($logo . $newsWidget, $viewUrl, ['title' => $model->name, 'class' => 'img-shimmer']);
                }
            } else {
                echo $logo;
            }
            ?>
        </div>
        <?=
        ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => "/community/community/update?id=" . $model->id,
            'actionDelete' => "/community/community/delete?id=" . $model->id,
            'mainDivClasses' => '',
            'optionsDelete' => ['class' => 'delete-community-btn'],
            'labelDeleteConfirm' =>AmosCommunity::t('amoscommunity', 'delete_confirm'),
            'additionalButtons' => $additionalButtons
        ]);
        ?>
        <?php if (!$fixedCommunityType) : ?>
            <?php
            switch ($model->community_type_id):
                case CommunityType::COMMUNITY_TYPE_CLOSED:
                    $classType = 'closed';
                    $textCommunityType = AmosCommunity::t('amoscommunity', 'Community ristretta ai partecipanti');
                    $iconCommunityType = 'am am-eye-off';
                    break;
                case CommunityType::COMMUNITY_TYPE_OPEN:
                    $classType = 'open';
                    $textCommunityType = AmosCommunity::t('amoscommunity', 'Community aperta');
                    $iconCommunityType = 'am am-lock-open';
                    break;
                case CommunityType::COMMUNITY_TYPE_PRIVATE:
                    $classType = 'private';
                    $textCommunityType = AmosCommunity::t('amoscommunity', 'Community riservata ai partecipanti');
                    $iconCommunityType = 'am am-lock-outline';
                    break;
                default:
                    $iconCommunityType = 'am am-accounts-alt';
            endswitch;
            ?>
            <?php if (!empty($urlRedirect)) { ?>
                <a href="<?= $urlRedirect ?>" class="position-absolute category<?= $model->communityType ?>" data-toggle="tooltip" title="<?= $textCommunityType ?>" target="_blank">
                    <span class="icon icon-white icon-sm <?= $iconCommunityType ?>"></span>
                </a>
            <?php } else { ?>
                <a href="<?= $viewUrl ?>" class="position-absolute category<?= $model->communityType ?>" data-toggle="tooltip" title="<?= $textCommunityType ?>">
                    <span class="icon icon-white icon-sm <?= $iconCommunityType ?>"></span>
                </a>
            <?php } ?>
        <?php endif; ?>
    </div>
    <div class="community-title-container py-3">
        <h3 class="community-title title-two-line h6 font-weight-bold m-t-0 m-b-0 text-center overflow-hidden">
            <?php if (!$isGuest) : ?>
                <?php if ($urlRedirect) : ?>
                    <?=
                    Html::a(
                        $model->name,
                        $urlRedirect,
                        [
                            'title' => AmosCommunity::t('amoscommunity', '#icon_title_link') . ' ' . $model->name, 'class' => 'title-two-line link-list-title',
                            'target' => '_blank'
                        ]
                    );
                    ?>
                <?php else : ?>
                    <?=
                    Html::a(
                        $model->name,
                        $viewUrl,
                        [
                            'title' => AmosCommunity::t('amoscommunity', '#icon_title_link') . ' ' . $model->name, 'class' => 'title-two-line link-list-title',
                        ]
                    );
                    ?>
                <?php endif; ?>
            <?php else : ?>
                <?= $model->name ?>
            <?php endif; ?>
        </h3>
    </div>
    <?php
    if (!$hideCommunityType) :
    ?>
        <?php if (!$fixedCommunityType) : ?>
            <?php if (!$isGuest) : ?>
                <?php if ($isSigned) : ?>
                    <small class="d-flex align-items-end text-muted mt-4">
                        <?= AmosCommunity::t('amoscommunity', 'Iscritto il') . ' ' ?><?= \Yii::$app->formatter->asDate($userCommunity->created_at, 'php:d M Y') ?>
                    </small>
                    <div class="button-container d-flex justify-content-center border-top">
                        <a href="<?= $viewUrl ?>" class="btn btn-xs btn-link" title="Visita la community <?= $model->name ?>"><?= AmosCommunity::t('amoscommunity', 'Visita la community') ?></a>
                    </div>
                <?php else : ?>
                    <?php if ($isOpenCommunity) : ?>
                        <div class="button-container d-flex justify-content-center border-top">
                            <a href="<?= $viewUrl ?>" class="btn btn-xs btn-primary" title="Visita la community <?= $model->name ?>"><?= AmosCommunity::t('amoscommunity', 'Visita la community') ?></a>
                        </div>
                    <?php elseif ($isPrivateCommunity) : ?>
                        <?php if ($isWaitingToSigned) : ?>
                            <small class="d-flex align-items-end text-muted mt-4">
                                <?= AmosCommunity::t('amoscommunity', 'Richiesta iscrizione inviata') ?>
                                <a href="javascript::void(0)" class="bi-form-field-tooltip-info m-l-5" data-toggle="tooltip" data-html="true" data-original-title="<?= AmosCommunity::t('amoscommunity', 'Sei in attesa che un community manager convalidi la richiesta per poter accedere alla community') ?>">
                                    <span class="am am-info-outline"></span>
                                    <span class="sr-only"><?= AmosCommunity::t('amoscommunity', 'Sei in attesa che un community manager convalidi la richiesta per poter accedere alla community') ?></span>
                                </a>
                            </small>
                        <?php endif ?>
                        <div class="button-container d-flex justify-content-center border-top">
                            <a href="<?= $viewUrl ?>" class="btn btn-xs btn-primary" title="Visita la community <?= $model->name ?>"><?= AmosCommunity::t('amoscommunity', 'Visita la community') ?></a>
                        </div>
                    <?php else : ?>
                        <?php if ($isWaitingToSigned) : ?>
                            <div class="button-container d-flex justify-content-center border-top">
                                <a href="<?= $viewUrl ?>" class="btn btn-xs btn-warning" title="Clicca per accettare o rifiutare l'invito alla community <?= $model->name ?>"><?= AmosCommunity::t('amoscommunity', 'Rispondi all\'invito') ?></a>
                            </div>
                        <?php else : ?>
                            <div class="button-container d-flex justify-content-center border-top">
                                <a href="<?= $viewUrl ?>" class="btn btn-xs btn-primary" title="Visita la community <?= $model->name ?>"><?= AmosCommunity::t('amoscommunity', 'Visita la community') ?></a>
                            </div>
                        <?php endif ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else : ?>
                <?php
                $socialAuthModule = Yii::$app->getModule('socialauth');
                $labelSigninOrSignup = AmosCommunity::t('amoscommunity', 'Accedi o registrati');
                if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                    $labelSigninOrSignup = AmosCommunity::t('amoscommunity', 'Accedi');
                }
                $tooltipPreventCtaView = AmosCommunity::t('amoscommunity',
                    '{labelSigninOrSignup} alla piattaforma {platformName} per visitare la community',
                    ['labelSigninOrSignup' => $labelSigninOrSignup, 'platformName' => \Yii::$app->name]
                );
                ?>
                <div class="button-container w-100 d-flex justify-content-center border-top">
                    <a href="javascript::void(0)" class="btn btn-primary btn-xs mt-2 disabled disabled-with-pointer-events" data-toggle="tooltip" data-html="true" data-original-title="<?= $tooltipPreventCtaView ?>">
                        <?= AmosCommunity::t('amoscommunity', 'Visita la community') ?>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>