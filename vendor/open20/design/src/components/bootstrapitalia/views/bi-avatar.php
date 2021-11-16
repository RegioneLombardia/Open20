<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$arr         = explode(' ', trim($nameSurname));
$name        = $arr[0];
$surname     = $arr[1];
$initials    = substr($name, 0, 1) . substr($surname, 0, 1);
$titlelink   = 'Visualizza il profilo di' . ' ' . $nameSurname;
$tooltipText = '<strong>' . $nameSurname . '</strong>' . (isset($tooltipAdditionalInfo) ? '<br/><em>' . $tooltipAdditionalInfo . '</em>' : '');
$removeLink  = (isset($removeLink)) ? $removeLink : false;
$showWelcomeLabel = (isset($showWelcomeLabel)) ? $showWelcomeLabel : false;
$welcomeLabel  = (isset($welcomeLabel)) ? $welcomeLabel : Module::t('amosdesign', 'Ciao');
$hideNameSurname = (isset($hideNameSurname)) ? $hideNameSurname : false;
$hideStatusValidated = (isset($hideStatusValidated)) ? $hideStatusValidated : true;
$showAvatarPresence = (isset($showAvatarPresence)) ? $showAvatarPresence : false;

$avatarPresence = (isset($avatarPresence)) ? 'active' : 'hidden';
if ($avatarPresence == 'hidden') {
    $avatarPresenceTitle = 'Assente';
} else {
    $avatarPresenceTitle = 'Attivo';
}

$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'md';
if ($avatarWrapperSize == 'xl') {
    $avatarClassSize = 'size-xl';
    $extraTextSize   = (isset($extraTextSize)) ? $extraTextSize : 'lead';
    $nameSize        = 'h5';
} else if ($avatarWrapperSize == 'lg') {
    $avatarClassSize = 'size-lg';
    $extraTextSize   = (isset($extraTextSize)) ? $extraTextSize : '';
    $nameSize        = 'h5';
} else if ($avatarWrapperSize == 'sm') {
    $avatarClassSize = 'size-sm';
    $extraTextSize   = (isset($extraTextSize)) ? $extraTextSize : 'small';
    $initials        = substr($name, 0, 1);
} else if ($avatarWrapperSize == 'xs') {
    $avatarClassSize = 'size-xs';
    $extraTextSize   = (isset($extraTextSize)) ? $extraTextSize : 'small';
    $initials        = substr($name, 0, 1);
} else {
    $avatarClassSize = 'size-md';
    $extraTextSize   = (isset($extraTextSize)) ? $extraTextSize : 'small';
}

$statoProfilo = (isset($statoProfilo)) ? $statoProfilo : 'Validato';
if ($statoProfilo == 'Validato') {
    $statoProfiloIcon = 'check';
    $statoProfiloClass = 'success';
} else if ($statoProfilo == 'Non validato') {
    $statoProfiloIcon = 'minus';
    $statoProfiloClass = 'danger';
} else if ($statoProfilo == 'Modifica in corso') {
    $statoProfiloIcon = 'autorenew';
    $statoProfiloClass = 'secondary';
} else {
    $statoProfiloIcon = 'exclamation-thick';
    $statoProfiloClass = 'warning';
}

if(!empty($model)){
    $nameSurname = $model->nomeCognome;
    $imageAvatar = $model->getAvatarUrl('card_users');
    $url = \open20\amos\admin\AmosAdmin::getModuleName().'/user-profile/view?id='.$model->id;
}



$buttonCtaMsgClass  = (isset($buttonCtaMsgClass)) ? $buttonCtaMsgClass : 'btn-primary';
$buttonCtaJoinClass = (isset($buttonCtaJoinClass)) ? $buttonCtaJoinClass : 'btn-secondary';
?>

<div class="avatar-wrapper avatar-extra-text mb-0  justify-content-start <?= $additionalClass ?> <?= $singleElementsWidthFullsize ?> <?= $singleElementsWidthMobile ?>">
    <?php if (!$removeLink || $showTooltip) : ?>
        <div class="avatar-box-img position-relative avatar <?= $avatarClassSize ?>">
            <a href="<?= ($removeLink == true ? 'javascript::void(0)' : $url) ?>" class="avatar <?= $avatarClassSize ?>" <?= isset($showTooltip) ? 'data-toggle="tooltip" data-html="true" ' : '' ?> title="<?= isset($showTooltip) ? $tooltipText : $titlelink ?>">
                <?php if (!(isset($imageAvatar))) : ?>
                    <p aria-hidden="true"><?= $initials ?></p>
                    <span class="sr-only"><?= $nameSurname ?></span>
                <?php else : ?>
                    <img src="<?= $imageAvatar ?>" alt="<?= $nameSurname ?>">
                <?php endif ?>


            </a>
            <?php if ($showAvatarPresence) : ?>
            <div class="avatar-presence <?= $avatarPresence ?>" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Presenza') . ': ' .  $avatarPresenceTitle ?>">
                <span class="sr-only"><?= Module::t('amosdesign', 'Presenza: ') . $avatarPresenceTitle ?> </span>
            </div>
            <?php endif ?>
            <?php if (!($statoProfilo == 'Validato' && $hideStatusValidated)) : ?>
                <div class="avatar-status bg-<?= $statoProfiloClass ?>" data-toggle="tooltip" title="Stato profilo utente: <?= $statoProfilo ?>">
                    <svg class="icon icon-white">
                        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $statoProfiloIcon ?>"></use>
                    </svg>
                    <span class="sr-only"><?= $statoProfilo ?></span>
                </div>
            <?php endif; ?>
            <?php if ($avatarWrapperSize == 'xl') : ?>
                <div class="info-box-avatar">
                    <?php if (($isFacilitator)) : ?>
                        <div class="info-avatar bg-tertiary" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Facilitatore') ?>">
                            <svg class="icon icon-white">
                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#assistant"></use>
                            </svg>
                            <span class="sr-only"><?= Module::t('amosdesign', 'Facilitatore') ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (($isCommunityManager)) : ?>
                        <div class="info-avatar bg-tertiary" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Community Manager') ?>">
                            <svg class="icon icon-white">
                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#account-supervisor"></use>
                            </svg>
                            <span class="sr-only"><?= Module::t('amosdesign', 'Community Manager') ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="avatar-box-img position-relative avatar <?= $avatarClassSize ?>">
            <?php if (!(isset($imageAvatar))) : ?>
                <p aria-hidden="true"><?= $initials ?></p>
                <span class="sr-only"><?= $nameSurname ?></span>
            <?php else : ?>
                <img src="<?= $imageAvatar ?>" alt="<?= $nameSurname ?>">
            <?php endif ?>
            <?php if ($avatarWrapperSize == 'xl') : ?>
                <div class="info-box-avatar">
                    <?php if (($isFacilitator)) : ?>
                        <div class="info-avatar bg-tertiary" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Facilitatore') ?>">
                            <svg class="icon icon-white">
                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#assistant"></use>
                            </svg>
                            <span class="sr-only"><?= Module::t('amosdesign', 'Facilitatore') ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (($isCommunityManager)) : ?>
                        <div class="info-avatar bg-tertiary" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Community Manager') ?>">
                            <svg class="icon icon-white">
                                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#account-supervisor"></use>
                            </svg>
                            <span class="sr-only"><?= Module::t('amosdesign', 'Community Manager') ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="ml-2 <?= $extraTextSize ?> ">
    
        <?php if (!$hideNameSurname) : ?>
            <?php if ($showWelcomeLabel) : ?>
                <span><?= $welcomeLabel . ',' ?></span>
                <?php endif ?>
            <?php if ($removeLink) : ?>
                <span class="font-weight-bold mb-0 avatar-name <?= $nameSize ?>"><?= $nameSurname ?></span>
            <?php else : ?>
                <span class="font-weight-bold mb-0 avatar-name <?= $nameSize ?>"><a href="<?= $url ?>" class="namesurname" title="<?= $titlelink ?>"><?= $nameSurname ?></a></span>
            <?php endif ?>
        <?php endif; ?>
        <?php if (!empty($additionalInfo)) : ?>
            <p class="additional-info font-weight-normal mb-0 text-secondary small"><?= $additionalInfo ?></p>
        <?php endif; ?>
        <?php if (!empty($scopes)) : ?>
            <p class="additional-info font-weight-normal mb-0 text-secondary small"><?= $scopes ?></p>
        <?php endif; ?>

        <?php if (isset($showCtaMsg)) : ?>
            <a href="<?= $urlCtaMsg ?>" class="btn <?= $buttonCtaMsgClass ?> btn-xs py-1" title="<?= $ctaTitleMsg ?> <?= ' ' . $nameSurname ?>"><?= $ctaMsg ?></a>
        <?php endif; ?>

        <?php if (isset($showCtaJoin)) : ?>
            <a href="<?= $urlCtaJoin ?>" class="btn <?= $buttonCtaJoinClass ?> btn-xs py-1" title="<?= $ctaJoinMsg ?> <?= ' ' . $nameSurname ?> "> <?= $ctaJoinMsg ?></a>
        <?php endif; ?>

    </div>
</div>