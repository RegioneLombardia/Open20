<?php

use open20\design\utility\DateUtility;
use open20\design\Module;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$hideNameSurname = (isset($hideNameSurname)) ? $hideNameSurname : true;
$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'sm';
$date              = DateUtility::getDate($date);
$updatedAt         = DateUtility::getDate($lastActivityDate);
$showAvatar        = (isset($showAvatar)) ? $showAvatar : true;
$numberExpose      = (isset($numberExpose) ? $numberExpose : 4);
$widthColumn = (isset($widthColumn)) ? $widthColumn :  'col-12';
?>



<div class="discussion-container py-3 border-bottom border-light w-100 <?= $widthColumn ?>">
    <div class="row align-items-center variable-gutters">
        <div class="generic-info d-flex col-12">
            <h3 class="discussion-title h6 lead mb-2 font-weight-semibold">
                <a class="title-one-line link-list-title" href="<?= $url ?>" title="partecipa alla discussione <?= $title ?>"><?= $title ?></a>

            </h3>
            <div class="other-info-item d-flex align-items-center justify-content-end ml-auto">
                <?php if (($newPubblication)) : ?>
                    <div class="ml-2">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                        );
                        ?>
                    </div>
                <?php endif; ?>
                <div class="ml-2">
                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                        [
                        'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model,
                            $actionModify, $actionDelete)
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?php if (isset($communityTitle)) : ?>
                <div class="mb-1 community-title small text-muted"><strong><?=Module::t('amosdesign','Community' . ':')?></strong> <?= $communityTitle ?></div>
            <?php endif ?>
            <div class="other-info d-flex small">
                <div class="d-flex flex-wrap align-items-center">
                    <svg class="icon icon-xs d-flex  mr-1" role="img" aria-label="Numero visite">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#calendar"></use>
                    </svg>
                    <div class="pr-3 mb-0 "><?= $date ?></div>
                </div>
                <a class="read-more d-inline ml-auto ml-md-0" href="<?= $url ?>" title="<?= Module::t('amosdesign',
                'Partecipa alla discussione')
            ?> <?= $title ?>"><?= Module::t('amosdesign', 'Partecipa') ?></a>
            </div>
        </div>


        <div class="col-md-2 col-sm-6">

            <div class="user-list-container d-flex">
                    <?php if (!empty($showAvatar)) : ?>
                        <div class="admin-container">
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                                [
                                'nameSurname' => $nameSurname,
                                'imageAvatar' => $imageAvatar,
                                'url' => $urlAvatar,
                                'additionalInfo' => $additionalInfoAvatar,
                                'tooltipAdditionalInfo' => isset($tooltipAdditionalInfoAvatar) ?: Module::t('amosdesign','Creatore'),
                                'removeLink' => $removeLink,
                                'avatarWrapperSize' => $avatarWrapperSize,
                                'extraTextSize' => $extraTextSize,
                                'hideNameSurname' => $hideNameSurname,
                                'showTooltip' => $showTooltip,
                                ]
                            );
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- < ?php if (!empty($avatarsGroup)) { ?>
                        <div class="partecipant-list">
                            < ?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-group-stacked',
                                [
                                'numberExpose' => $numberExpose,
                                'usersNumber' => $partecipantNumber,
                                'userProfiles' => $avatarsGroup,
                                'tooltipAdditionalInfoAvatarsGroup' => isset($tooltipAdditionalInfoAvatarsGroup) ?: Module::t('amosdesign',
                                        'Partecipante'),
                                'removeLink' => $removeLink,
                                ]
                            );
                            ?>
                        </div>
                    < ?php } ?> -->
            </div>


        </div>

        <div class="col-md-4 col-sm-6 small">

            <div class="justify-content-between d-flex">
                <div aria-label="Numero di risposte alla discussione:  <?= $numbersOfAnswer ?> " class="d-flex align-items-center" data-toggle="tooltip" title="Numero di risposte alla discussione">
                    <svg class="icon icon-xs d-flex  mr-1" role="img" aria-label="Numero risposte">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#message-outline"></use>
                    </svg>
<?= $numbersOfAnswer ?>
                </div>
                <div aria-label="Numero di visite alla discussione: " class="d-flex align-items-center" data-toggle="tooltip" title="Numero visite">
                    <svg class="icon icon-xs d-flex  mr-1" role="img" aria-label="Numero visite">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#eye-outline"></use>
                    </svg>
                            
                    <?= !empty($numbersOfVisits) ? $numbersOfVisits : '0' ?>

                </div>
                <div aria-label="Ultima attività nella discussione: " class="d-flex align-items-center" data-toggle="tooltip" title="Ultima attività">
                    <svg class="icon icon-xs d-flex mr-1" role="img" aria-label="Ultima attività">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#clock-outline"></use>
                    </svg>
<?= $updatedAt ?>
                </div>
            </div>

        </div>
    </div>
</div>