<?php

use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

//https://www.php.net/manual/en/datetime.format.php
$lastSyncDrive = '';
if (!empty($dateSyncDrive)) {
    $dateLastSyncDrive = DateUtility::getDate($dateSyncDrive, 'php:d/m/Y');
    $hourLastSyncDrive = DateUtility::getDateHour($dateSyncDrive, 'php:H:i');
    $lastSyncDrive     = Module::t('amosdesign', 'Documento Google Drive').'<br>'.Module::t('amosdesign',
            'aggiornato il').$dateLastSyncDrive.Module::t('amosdesign', 'alle').$hourLastSyncDrive;
}

if(isset($typeFolder)){
    $type = $typeFolder ? 'FOLDER' :$type;
}
$type = strtoupper($type);
if (!empty($date)) {
    $date = DateUtility::getDate($date);
}
$customTooltipInfo = (!empty($customTooltipInfo)) ? $customTooltipInfo.' ('.$type.' - '.$size.')' : '';



$infoDoc     = '<strong>'.Module::t('amosdesign', 'Nome file principale').'</strong>'.' '.$fileName.'<br>';
$infoDoc     = (!empty($nameSurname)) ? $infoDoc.'<strong>'.Module::t('amosdesign', 'Pubblicato da').'</strong>'.' '.$nameSurname.' '.(!empty($date)
        ? Module::t('amosdesign', 'il').' '.$date : '').'<br>' : $infoDoc;
$infoDoc     = (!empty($category)) ? $infoDoc.'<strong>'.Module::t('amosdesign', 'nella categoria').'</strong>'.' '.$category.'<br>'
        : $infoDoc;
$infoDoc     = (!empty($community)) ? $infoDoc.' '.'<strong>'.Module::t('amosdesign', 'in community').'</strong>'.' '.$community
        : $infoDoc;
$widthColumn = (!empty($widthColumn)) ? $widthColumn : 'col-md-4 col-sm-6';
$allegatiNum = (!empty($allegatiNum)) ? Module::t('amosdesign', 'Allegati interni').' '.$allegatiNum : '';

$model        = (!empty($model) ? $model : null);
$actionModify = (!empty($actionModify) ? $actionModify : null);
$actionDelete = (!empty($actionDelete) ? $actionDelete : null);
?>
<div class="<?= $widthColumn ?>">
    <div class="card-wrapper card-space documenti-card-wrapper pb-4 <?= ($type == 'FOLDER') ? 'card-type-folder' : ''; ?>">
        <div class="card card-bg">
            <div class="card-body">
                <div class="categoryicon-top">

                    <?php
                    if (!empty($type)) {
                        if (in_array($type, ['JPG', 'PNG', 'JPEG', 'SVG'])) :
                            ?>
                            <svg class="icon icon-image">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-image"></use>
                            </svg>
                        <?php elseif (in_array($type, ['PDF'])) : ?>
                            <svg class="icon icon-pdf">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-pdf"></use>
                            </svg>
                        <?php elseif (in_array($type, ['DOC', 'DOCX'])) : ?>
                            <svg class="icon icon-word">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-word"></use>
                            </svg>
                        <?php elseif (in_array($type, ['TXT', 'RTF', 'LOG'])) : ?>
                            <svg class="icon icon-txt">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-document"></use>
                            </svg>
                        <?php elseif (in_array($type, ['XLS', 'XLSX'])) : ?>
                            <svg class="icon icon-excel">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-excel"></use>
                            </svg>
                        <?php elseif (in_array($type, ['ZIP', 'RAR'])) : ?>
                            <svg class="icon icon-secondary">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder-zip"></use>
                            </svg>
                        <?php elseif (in_array($type, ['FOLDER'])) : ?>
                            <svg class="icon icon-folder">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder"></use>
                            </svg>
                        <?php else : ?>
                            <svg class="icon icon-secondary">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-link"></use>
                            </svg>
                        <?php
                        endif;
                    } else {
                        ?>
                        <svg class="icon icon-secondary">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-link"></use>
                        </svg>
                    <?php }
                    ?>

                    <?php if (!empty($dateSyncDrive)) : ?>
                        <svg class="icon icon-xs icon-overlay bg-google-drive icon-padded rounded-circle icon-white" data-toggle="tooltip" data-html="true" title="<?= $lastSyncDrive ?>">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#google-drive"></use>
                        </svg>
                    <?php endif ?>

                    <?php if (!empty($link_document) && ($type != 'FOLDER')) : ?>
                        <span class="text mr-1"><?= Module::t('amosdesign', 'LINK ESTERNO') ?></span>
                    <?php elseif (empty($size) && ($type == 'FOLDER')) : ?>
                        <span class="text mr-1"><?= Module::t('amosdesign', 'CARTELLA') ?></span>
                    <?php else : ?>
                        <span class="text mr-1"><?= $type ?></span>
                    <?php endif ?>

                    <?php
                        if (!empty($size)) :
                        //if($size > 1023
                    ?>
                        <span class="text text-capitalize">(<?= $size ?>KB)</span>
                    <?php endif ?>

                    <div class="info-doc-top-right ml-auto d-flex align-items-center">

                        <?php if (!empty($allegatiNum)) : ?>
                            <div class="allegatiNum">
                                <svg class="icon icon-sm icon-secondary" data-toggle="tooltip" title="<?= $allegatiNum ?>">
                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#paperclip"></use>
                                </svg>
                            </div>

                        <?php endif; ?>
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
                <!-- fix temporaneo per demo: da verificare-->
                <?php
                if (!empty($url) && !($type == 'FOLDER')) {
                    ?>
                    <a href="<?= $url ?>" class="link-list-title" title="<?=
                    Module::t('amosdesign', 'Dettaglio documento')
                    ?> <?= $title ?>">
                        <h6 class="card-title mb-2 title-three-line"><?= $title ?></h6>
                    </a>

                    <?php
                }else{
                    if(($type == 'FOLDER')){?>
                    
                    <?=
                    \open20\amos\core\helpers\Html::beginTag('a', array_merge($fileUrl, ['class' => 'link-list-title']))
                    ?>  <h6 class="card-title mb-2 title-three-line"><?= $title ?></h6> <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                    <?php }
                }
                if (!empty($url) && !empty($newPubblication)) :
                    ?>

                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                    );
                    ?>
                <?php endif; ?>
                <?php if (!empty($versionFile)) : ?>
                    <div class="blockquote-footer"><cite title="versione file"><?= Module::t('amosdesign', 'versione') ?> <?= $versionFile ?></cite></div>

                <?php endif ?>
                <!-- <div class="card-text text-sans-serif">< ?= $infoDoc ?></div> -->

                <?php if ($type == 'FOLDER') : ?>
                    <?=
                    \open20\amos\core\helpers\Html::beginTag('a', array_merge($fileUrl, ['class' => 'read-more']))
                    ?>  <?= Module::t('amosdesign', 'Apri cartella') ?> <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                <?php elseif (!empty($link_document) && ($type != 'FOLDER')) : ?>
                    <?=
                    \open20\amos\core\helpers\Html::beginTag('a', array_merge($fileUrl, ['class' => 'read-more']))
                    ?>  <?= Module::t('amosdesign', 'Apri file')
                    ?> <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                <?php else : ?>
                    <?=
                    \open20\amos\core\helpers\Html::beginTag('a',
                        array_merge($fileUrl,
                            ['class' => 'read-more', 'data-toggle' => 'tooltip', 'title' => Module::t('amosdesign',
                            'Scarica il documento principale').' '.$fileName]))
                    ?>
                    <?= Module::t('amosdesign', 'Scarica file') ?>
                    <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                <?php endif ?>

                <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" title="<?= $infoDoc ?>" class="info-doc">
                    <svg class="icon icon-sm icon-info">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#information-outline"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>