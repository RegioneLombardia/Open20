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

$model     = (isset($model) ? $model : null);
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);

?>



<div class="discussion-container py-3 border-bottom border-light w-100 <?= $widthColumn ?>">
    <div class="row align-items-center variable-gutters">
        <div class="generic-info d-flex col-12">
            <div class="d-flex align-items-center">
                <h3 class="discussion-title h6 lead mb-0 font-weight-semibold">
                    <a class="title-one-line link-list-title" href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Partecipa alla discussione {title} ',['title' => $title]) ?>"><?= $title ?></a>

                </h3>
                <?php if (!empty($contentScopesAvatar)) : ?>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="<?= $contentScopesAvatar ?>">
                        <svg class="icon icon-sm icon-primary" role="img" aria-label="News in community">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#account-supervisor-circle"></use>
                        </svg>
                        <span class="sr-only"><?= $contentScopesAvatar ?></span>
                    </a>
                <?php endif; ?>
            </div>
            
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
                            $actionModify, $actionDelete),
                            'model' => $model
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
           
            <div class="other-info d-flex small">
                <div class="d-flex flex-wrap align-items-center">
                    <svg class="icon icon-xs d-flex  mr-1" role="img" aria-label="<?= Module::t('amosdesign', 'Numero di visite') ?>">
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
                

                <div aria-label="<?= Module::t('amosdesign', 'Numero di risposte alla discussione: {numbersOfAnswer} ',['numbersOfAnswer' => $numbersOfAnswer]) ?>" class="d-flex align-items-center" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Numero di risposte alla discussione: {numbersOfAnswer} ',['numbersOfAnswer' => $numbersOfAnswer]) ?>">
                    <svg class="icon icon-xs d-flex  mr-1" role="img" aria-label="<?= Module::t('amosdesign', 'Numero di risposte alla discussione: {numbersOfAnswer} ',['numbersOfAnswer' => $numbersOfAnswer]) ?>">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#message-outline"></use>
                    </svg>
                    <?= $numbersOfAnswer ?>
                </div>
                
                <div aria-label="<?= Module::t('amosdesign', 'Numero di visite alla discussione: {numbersOfVisits} ',['numbersOfAnswer' => !empty($numbersOfVisits) ? $numbersOfVisits : '0']) ?>" class="d-flex align-items-center" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Numero di visite alla discussione: {numbersOfVisits} ',['numbersOfAnswer' => !empty($numbersOfVisits) ? $numbersOfVisits : '0']) ?>">
                    <svg class="icon icon-xs d-flex  mr-1" role="img" aria-label="<?= Module::t('amosdesign', 'Numero di visite alla discussione: {numbersOfVisits} ',['numbersOfAnswer' => !empty($numbersOfVisits) ? $numbersOfVisits : '0']) ?>">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#eye-outline"></use>
                    </svg>     
                    <?= !empty($numbersOfVisits) ? $numbersOfVisits : '0' ?>
                </div>
                

                <div aria-label="<?= Module::t('amosdesign', 'Ultima attività nella discussione:  {updatedAt} ',['updatedAt' => $updatedAt]) ?>" class="d-flex align-items-center" data-toggle="tooltip" title="<?= Module::t('amosdesign', 'Ultima attività nella discussione:  {updatedAt} ',['updatedAt' => $updatedAt]) ?>">
                    <svg class="icon icon-xs d-flex mr-1" role="img" aria-label="<?= Module::t('amosdesign', 'Ultima attività nella discussione:  {updatedAt} ',['updatedAt' => $updatedAt]) ?>">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#clock-outline"></use>
                    </svg>
                    <?= $updatedAt ?>
                </div>
            </div>

        </div>
    </div>
</div>