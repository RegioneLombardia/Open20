<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-profile
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\interfaces\OrganizationsModuleInterface;
use open20\amos\core\user\User;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\layout\Module;
use open20\amos\core\utilities\StringUtils;
use open20\amos\admin\widgets\MiniStatusIconWidget;
use open20\amos\admin\widgets\ConnectToUserWidget;
use open20\amos\admin\widgets\SendMessageToUserWidget;


/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

/** @var AmosAdmin $adminModule */
$adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

/** @var OrganizationsModuleInterface $organizationsModule */
$organizationsModule = Yii::$app->getModule($adminModule->getOrganizationModuleName());

if ($model instanceof User) {
    $model = $model->getProfile();
}
$nomeCognome = '';
if ($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) {
    if ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= $model->nome;
    }
    if ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)) {
        $nomeCognome .= ' ' . $model->cognome;
    }
}

$isVisibleEmail = (($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) &&
    ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) &&
    ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW)) &&
    $model->user->email && (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI')));

$confirmText = AmosAdmin::t('amosadmin', 'You have selected') . " " . $model->getNomeCognome() . " " . AmosAdmin::t('amosadmin', 'as your facilitator. To confirm click on the CONFIRM button. At the confirm the facilitator will be bound to the user profile.');

$isAssociateFacilitator = (Yii::$app->controller->action->id == 'associate-facilitator') || (Yii::$app->controller->action->id == 'send-request-external-facilitator');
$urlConfirmBtn = Yii::$app->controller->action->id == 'send-request-external-facilitator'
    ?  '/'.AmosAdmin::getModuleName().'/user-profile/send-request-external-facilitator?idToAssign='.$model->id .'&id='.\Yii::$app->request->get('id')
    : null;

if ($isAssociateFacilitator) {
    ModalUtility::createConfirmModal([
        'id' => 'confirmPopup' . $model->id,
        'modalDescriptionText' => $confirmText,
        'confirmBtnOptions' => ['id' => 'confirm-associate-facilitator', 'class' => 'btn btn-primary confirmBtn', 'data' => ['model_id' => $model->id]],
        'confirmBtnLink' => $urlConfirmBtn
    ]);
}

$modelFullViewUrl = $model->getFullViewUrl();



$jsReadMore = <<< JS

$("#moreTextJs .changeContentJs > .actionChangeContentJs").click(function(){
    $("#moreTextJs .changeContentJs").toggle();
    $('html, body').animate({scrollTop: $('#moreTextJs').offset().top - 120},1000);
});
JS;
$this->registerJs($jsReadMore);
?>



<div class="list-horizontal-element flexbox p-t-15 p-b-15 ">

    <div class="avatar-wrapper avatar-extra-text mb-0 flexbox m-r-15 ">
        <div class="avatar-box-img">
            <?php
            if (($adminModule->confManager->isVisibleBox('box_foto', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                ($adminModule->confManager->isVisibleField('userProfileImage', ConfigurationManager::VIEW_TYPE_VIEW))
            ) :
            ?>
                <?php
                $url                                       = $model->getAvatarUrl('card_users');
                Yii::$app->imageUtility->methodGetImageUrl = 'getAvatarUrl';
                $logoOptions                               = [
                    'class' => Yii::$app->imageUtility->getRoundImage($model)['class'],
                    //'style' => "margin-left: " . Yii::$app->imageUtility->getRoundImage($model)['margin-left'] . "%; margin-top: " . Yii::$app->imageUtility->getRoundImage($model)['margin-top'] . "%;",
                    'alt' => "",
                ];
                $options                                   = [];
                if (strlen($nomeCognome) > 0) {
                    $logoOptions['alt'] = AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $model->getNomeCognome();
                    $options['title']   = AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $model->getNomeCognome();
                    $options['class']   = 'avatar';
                }
                $logo = Html::img($url, $logoOptions);
                ?>
                <?= Html::a($logo, $viewUrl, $options); ?>
            <?php endif; ?>

           
            
            <?= MiniStatusIconWidget::widget([
                'model' => $model,
                'adminModule' => $adminModule
            ]); ?>
                
                <div class="info-box-avatar">
                <?php
                if ($model->isFacilitator()) {
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

                if (\open20\amos\admin\models\search\UserProfileSearch::isCommunityManagerOfAtLeastOne($model->user_id)) {
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
    </div>

    <div class="user-info-wrapper flexbox flexbox-column">
        <div class="first-row flexbox flex-wrap">
            <div class="info-container flexbox flex-wrap">
                <div class="name-manage flexbox flex-wrap">
                    <!-- name surname-->
                    <?php if (($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                        ($adminModule->confManager->isVisibleField('nome', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                        ($adminModule->confManager->isVisibleField('cognome', ConfigurationManager::VIEW_TYPE_VIEW))
                        ): ?>
                        <h3 class="h5 font-weight-bold m-b-5 m-r-10">
                            <?php if ($isAssociateFacilitator): ?>
                                <?= $nomeCognome ?>
                            <?php else: ?>
                                <?= Html::a($nomeCognome, $modelFullViewUrl, ['title' => AmosAdmin::t('amosadmin', '#icon_name_title_link') . ' ' . $model->getNomeCognome(), 'data-test' => 'list-view-profiles']); ?>
                            <?php endif; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (($adminModule->confManager->isVisibleBox('box_prevalent_partnership', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                        ($adminModule->confManager->isVisibleField('prevalent_partnership_id', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                        ((Yii::$app->controller->action->id == 'facilitator-users') || $isAssociateFacilitator)
                    ): ?>
                        <?php
                        $prevalentPartnership = $model->prevalentPartnership;
                        ?>
                        <?php if (!is_null($prevalentPartnership)): ?>
                            <!--collaborazione prevalente?-->
                            <a class="m-b-5 m-t-5" href="<?= $prevalentPartnership->getFullViewUrl() ?>" title="<?= AmosAdmin::t('amosadmin', 'Prevalent partnership') ?>: <?= $prevalentPartnership->getNameField() ?>" data-toggle="tooltip"><?= $prevalentPartnership->getNameField() ?></a>
                            <!-- <p>
                                < ?php
                                    $organizationsCardWidgetClassName = $organizationsModule->getOrganizationCardWidgetClass();
                                ?>
                                < ?= $organizationsCardWidgetClassName::widget(['model' => $model->prevalentPartnership]); ?>
                            </p> -->
                        <?php endif; ?>    
                    <?php endif; ?>
                        
                </div>
                <!-- <div class="tooltips">
                  
                    tooltip 1 facilitatori
                    < ?php if ($adminModule->confManager->isVisibleBox('box_facilitatori', ConfigurationManager::VIEW_TYPE_FORM) &&
                        $adminModule->confManager->isVisibleField('facilitatore_id', ConfigurationManager::VIEW_TYPE_FORM) &&
                        $isAssociateFacilitator
                        ): ?>
                        < ?php $facilitatorIcon = '<span class="mdi mdi-assistant"></span>';
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
                        ); ?>
                    < ?php endif; ?>
                    tooltip 2 community
                </div> -->
                
            </div>
            
            <!--manage menu-->
            <div class="new-manage-container d-flex first-row">
                <!--badge new-->
                <?=
                    \open20\amos\notificationmanager\forms\NewsWidget::widget([
                        'model' => $model,
                        'css_class' => 'badge badge-left m-b-5 m-r-5'
                    ]);
                ?>
                <?php if (!$isAssociateFacilitator): ?>
                    <div class="manage-container">
                        <?= ContextMenuWidget::widget([
                            'model' => $model,
                            'mainDivClasses' => 'pull-right',
                            'actionModify' => '/'.AmosAdmin::getModuleName().'/user-profile/update?id=' . $model->id,
                            'disableDelete' => true
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="second-row m-t-5 m-b-5">  
            <?php if ($isVisibleEmail): ?>
                
                <span class="am am-email" title="Email"></span>
                
                <a class="user-mail small" href="mailto:<?= $model->user->email ?>" title="Invia una mail a <?= $nomeCognome?>">
                    <span><?= $model->user->email; ?></span>
                </a>
            <?php endif; ?>
        </div>

        <div class="third-row m-t-5 m-b-5">  
            <?php $communityModule = Yii::$app->getModule('community'); ?>
            <?php if (!is_null($communityModule) && (Yii::$app->controller->action->id == 'community-manager-users')): ?>
                <?php /** @var \open20\amos\community\AmosCommunity $communityModule */ ?>
                <?php $userCommunities = UserProfileUtility::getCommunitiesForManagers($communityModule, $model->user_id); ?>
                <?php if (count($userCommunities)): ?>
                    <!--<span class="text-uppercase bold">< ?= AmosAdmin::t('amosadmin', 'Community manager for') . ':' ?></span>-->
                    <?php foreach ($userCommunities as $userCommunity): ?>
                        <?php /** @var \open20\amos\community\models\Community $userCommunity */ ?>
                        
                        <?php
                        if (!empty($userCommunity->communityLogo)) {
                            $urlLogo = $userCommunity->communityLogo->getWebUrl('item_community', false, true);
                        } else {
                            $urlLogo = '/img/img_default.jpg';
                        }
                        /* $communityIcon = '<span class="mdi mdi-account-supervisor m-l-5"></span>';
                        var_dump($userCommunity->communityLogo->attributes);
                        echo Html::tag(
                            'span',
                            $communityIcon,
                            [
                                'class' => 'icon-community',
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'bottom',
                                'data-html' => 'true',
                                'title' => 'test'
                            ]
                        ); */
                        ?>
                        <div class="community-image rounded m-b-5 m-r-15">
                            <a href="<?= '/community/join/open-join?id=' . $userCommunity->id ?>" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<?= Module::t('amosdesign', 'Vai alla community: ') . $userCommunity->name ?>">
                                <img alt="immagine community" class="h-100 w-100" src="<?= $urlLogo ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>


        <div class="fourth-row flexbox m-t-5">
            
            <!--description-->
            <?php if (($adminModule->confManager->isVisibleBox('box_informazioni_base', ConfigurationManager::VIEW_TYPE_VIEW)) &&
                ($adminModule->confManager->isVisibleField('presentazione_breve', ConfigurationManager::VIEW_TYPE_VIEW))
                ): ?>

                <?php
                    $desclen = 350;
                ?>
                <?php if (strlen($model->presentazione_breve) <= $desclen) : ?>
                    <p><?= $model->presentazione_breve ?></p>
                <?php else : ?>
                    <div id="moreTextJs">
                        <?php
                            $moreContentTextLink  = Module::t('amoslayout', 'espandi descrizione') . ' ' . AmosIcons::show("chevron-down");
                            $moreContentTitleLink = Module::t('amoslayout', 'Leggi la descrizione completa');

                            $lessContentTextLink  = Module::t('amoslayout', 'riduci descrizione') . ' ' . AmosIcons::show("chevron-up");
                            $lessContentTitleLink = Module::t('amoslayout', 'Riduci testo');
                        ?>
                        <div class="changeContentJs partialContent">
                            <?= StringUtils::truncateHTML($model->presentazione_breve, $desclen)?>
                            <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $moreContentTitleLink ?>"><?= $moreContentTextLink ?></a>
                        </div>
                        <div class="changeContentJs totalContent" style="display:none">
                                <?= $model->presentazione_breve ?>
                            <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $lessContentTitleLink ?>"><?= $lessContentTextLink ?></a>
                        </div>
                    </div>
                <?php endif; ?>

              
            <?php endif; ?>
            <!--read more-->
            <div class="pull-right">
                <!-- BEGIN BLOCCO PULSANTE SETTAGGIO FACILITATORE -->
                <?php if ($adminModule->confManager->isVisibleBox('box_facilitatori', ConfigurationManager::VIEW_TYPE_FORM) &&
                    $adminModule->confManager->isVisibleField('facilitatore_id', ConfigurationManager::VIEW_TYPE_FORM) &&
                    $isAssociateFacilitator
                ): ?>
                    <?= Html::a(
                        AmosIcons::show('square-check', ['class' => 'm-r-5'], 'dash') . AmosAdmin::t('amosadmin', 'Set as facilitator'),
                        null,
                        [
                            'class' => 'btn btn-navigation-primary set-facilitator-btn',
                            'data-model_id' => $model->id,
                            'data-model_name' => $model->nome,
                            'data-model_surname' => $model->cognome,
                            'data-target' => '#confirmPopup' . $model->id,
                            'data-toggle' => 'modal',
//                            'data-confirm' => $confirmText,
//                            'data-pjax' => 0
                        ]
                    ); ?>
                <?php endif; ?>
                <!-- END BLOCCO PULSANTE SETTAGGIO FACILITATORE -->
                
                <?php if (Yii::$app->user->id != $model->user_id) : ?>
                    <?php if ($adminModule->enableUserContacts && !$adminModule->enableSendMessage) : ?>
                        <?= ConnectToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => '']) ?>
                    <?php endif; ?>
                    <?php if (!$adminModule->enableUserContacts && $adminModule->enableSendMessage) : ?>
                        <?= SendMessageToUserWidget::widget(['model' => $model, 'divClassBtnContainer' => '']) ?>
                    <?php endif; ?>
                    <?php if ($adminModule->enableInviteUserToEvent) : ?>
                        <?php
                        /** @var \open20\amos\events\AmosEvents $eventsModule */
                        $eventsModule = Yii::$app->getModule('events');
                        ?>
                        <?php if (!is_null($eventsModule) && $eventsModule->hasMethod('getInviteUserToEventWidget')) : ?>
                            <?= $eventsModule->getInviteUserToEventWidget($model) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
        </div>
        
    </div>
</div>
