<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\icon_contacts
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\widgets\ConnectToUserWidget;
use open20\amos\admin\widgets\MiniStatusIconWidget;
use open20\amos\admin\widgets\SendMessageToUserWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;


$userProfile = $model->user->userProfile;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */
$userId        = $userProfile->user_id;


/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

$nomeCognome = '';
if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) {
    if ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= $userProfile->nome;
    }
    if ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= ' ' . $userProfile->cognome;
    }
}
$arr         = explode(' ', trim($nomeCognome));
$nome        = $arr[0];
$cognome     = $arr[1];
$initials    = strtoupper(substr($nome, 0, 1) . substr($cognome, 0, 1));

$viewUrl = "/" . AmosAdmin::getModuleName() . "/user-profile/view?id=" . $userProfile->id;


$prevalentPartnershipTruncated = '';
$prevalentPartnershipName      = '';
if (!is_null($userProfile->prevalentPartnership)) {
    $prevalentPartnershipTruncated = $userProfile->prevalentPartnership;
    $prevalentPartnershipName      = $userProfile->prevalentPartnership->name;
}
?>
<div class="avatar-wrapper avatar-extra-text mb-0">
    <div class="avatar-box-img">
        <?php
        if (($adminModule->confManager->isVisibleBox('box_foto', ConfigurationManager::VIEW_TYPE_VIEW)) &&
            ($adminModule->confManager->isVisibleField('userProfileImage', ConfigurationManager::VIEW_TYPE_VIEW))
        ) :
            ?>
            <?php
            $url                                       = $userProfile->getAvatarUrl('card_users');
            Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
            $logoOptions                               = [
                'class' => Yii::$app->imageUtility->getRoundImage($userProfile)['class'],
                //'style' => "margin-left: " . Yii::$app->imageUtility->getRoundImage($model)['margin-left'] . "%; margin-top: " . Yii::$app->imageUtility->getRoundImage($model)['margin-top'] . "%;",
                'alt' => "",
            ];
            $options                                   = [];
            if (strlen($nomeCognome) > 0) {
                $logoOptions['alt'] = AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $userProfile->getNomeCognome();
                $options['title']   = AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $userProfile->getNomeCognome();
                $options['class']   = 'avatar';
            }
            $logo = Html::img($url, $logoOptions);
            ?>
            <?= Html::a($logo, $viewUrl, $options); ?>
        <?php endif; ?>

        <?=
        \open20\amos\notificationmanager\forms\NewsWidget::widget([
            'model' => $userProfile,
            'css_class' => 'badge badge-left'
        ]);
        ?>

        <?= MiniStatusIconWidget::widget([
            'model' => $userProfile,
            'adminModule' => $adminModule
        ]); ?>

        <div class="info-box-avatar">
            <?php
            if ($userProfile->isFacilitator()) {
                $facilitatorIcon = '<span class="mdi mdi-assistant"></span>';
                echo Html::tag(
                    'span',
                    $facilitatorIcon,
                    [
                        'class' => 'icon-info-avatar icon-facilitator ',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'data-html' => 'true',
                        'title' => AmosAdmin::t('amosadmin', 'Facilitator')
                    ]
                );
            }

            if (\open20\amos\admin\models\search\UserProfileSearch::isCommunityManagerOfAtLeastOne($userProfile->user_id)) {
                $communityManagerIcon = '<span class="mdi mdi-account-supervisor"></span>';
                echo Html::tag(
                    'span',
                    $communityManagerIcon,
                    [
                        'class' => 'icon-info-avatar icon-community-manager ',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'bottom',
                        'data-html' => 'true',
                        'title' => AmosAdmin::t('amosadmin', 'Community Manager')
                    ]
                );
            }
            ?>
        </div>

    </div>

    <div class="ml-2 avatar-body">
        <div class="name-manage">
            <p class="avatar-name font-weight-bold mb-0"><?= Html::a(
                    $userProfile->getNomeCognome(),
                    $viewUrl,
                    ['title' => AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $userProfile->getNomeCognome(), 'data-gui' => 'icon-view-profiles']
                );
                ?>

            </p>

            <?=
            ContextMenuWidget::widget([
                'model' => $userProfile,
                'actionModify' => '/amosadmin/user-profile/update?id=' . $userProfile->id,
                'disableDelete' => true
            ])
            ?>

        </div>
        <?php
        if (
            ($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_VIEW))
            &&
            ($adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_VIEW))
        ) :
            ?>
            <!-- "additionalInfo" -->
            <small class="avatar-info font-weight-normal mb-0">
                <?= (!empty($prevalentPartnershipTruncated)) ? $prevalentPartnershipTruncated : ''; ?>
            </small>
        <?php endif; ?>

        <?php if (Yii::$app->user->id != $userProfile->user_id) : ?>
            <?php if ($adminModule->enableUserContacts && !$adminModule->enableSendMessage) : ?>
                <?= ConnectToUserWidget::widget(['model' => $userProfile, 'divClassBtnContainer' => '']) ?>
            <?php endif; ?>
            <?php if (!$adminModule->enableUserContacts && $adminModule->enableSendMessage) : ?>
                <?= SendMessageToUserWidget::widget(['model' => $userProfile, 'divClassBtnContainer' => '']) ?>
            <?php endif; ?>
            <?php if ($adminModule->enableInviteUserToEvent) : ?>
                <?php
                /** @var \open20\amos\events\AmosEvents $eventsModule */
                $eventsModule = Yii::$app->getModule('events');
                ?>
                <?php if (!is_null($eventsModule) && $eventsModule->hasMethod('getInviteUserToEventWidget')) : ?>
                    <?= $eventsModule->getInviteUserToEventWidget($userProfile) ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

    </div>

</div>