<?php

use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

//https://www.php.net/manual/en/datetime.format.php
$lastSyncDrive = '';
if (!empty($widget->dateSyncDrive)) {
    $dateLastSyncDrive     = DateUtility::getDate($widget->dateSyncDrive, 'php:d/m/Y');
    $hourLastSyncDrive     = DateUtility::getDateHour($widget->dateSyncDrive, 'php:H:i');
    $lastSyncDrive = Module::t('amosdesign', 'Documento Google Drive').'<br>'.Module::t('amosdesign',
            'aggiornato il').$dateLastSyncDrive.Module::t('amosdesign', 'alle').$hourLastSyncDrive;
}


$widget->type = strtoupper($widget->type);
if (!empty($widget->date)) {
    $date = DateUtility::getDate($widget->date);
}
$widget->customTooltipInfo = (!empty($widget->customTooltipInfo)) ? $widget->customTooltipInfo.' ('.$widget->type.' - '.$widget->size.')'
        : '';



$infoDoc             = '<strong>'.Module::t('amosdesign', 'Nome file principale').'</strong>'.' '.$widget->fileName.'<br>';
$infoDoc             = (!empty($widget->nameSurname)) ? $infoDoc.'<strong>'.Module::t('amosdesign', 'Pubblicato da').'</strong>'.' '.$widget->nameSurname.' '.(!empty($widget->date)
        ? Module::t('amosdesign', 'il').' '.$date : '').'<br>' : $infoDoc;
$infoDoc             = (!empty($widget->category)) ? $infoDoc.'<strong>'.Module::t('amosdesign', 'nella categoria').'</strong>'.' '.$widget->category.'<br>'
        : $infoDoc;
$infoDoc             = (!empty($widget->community)) ? $infoDoc.' '.'<strong>'.Module::t('amosdesign', 'in community').'</strong>'.' '.$widget->community
        : $infoDoc;
$widget->widthColumn = (!empty($widget->widthColumn)) ? $widget->widthColumn : 'col-md-4 col-sm-6';
$widget->allegatiNum = (!empty($widget->allegatiNum)) ? Module::t('amosdesign', 'Allegati interni').' '.$widget->allegatiNum
        : '';

$widget->model        = (!empty($widget->model) ? $widget->model : null);
$widget->actionModify = (!empty($widget->actionModify) ? $widget->actionModify : null);
$widget->actionDelete = (!empty($widget->actionDelete) ? $widget->actionDelete : null);
?>
<div class="<?= $widget->widthColumn ?>">
    <div class="card-wrapper card-space documenti-card-wrapper pb-4 <?=
    ($widget->type == 'FOLDER') ? 'card-type-folder' : '';
    ?>">
        <div class="card card-bg">
            <div class="card-body">
                <div class="categoryicon-top">

                    <?php
                    if (!empty($widget->type)) {
                        if (in_array($widget->type, ['JPG', 'PNG', 'JPEG', 'SVG'])) :
                            ?>
                            <svg class="icon icon-image">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-image"></use>
                            </svg>
                        <?php elseif (in_array($widget->type, ['PDF'])) : ?>
                            <svg class="icon icon-pdf">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-pdf"></use>
                            </svg>
                        <?php elseif (in_array($widget->type, ['DOC', 'DOCX'])) : ?>
                            <svg class="icon icon-word">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-word"></use>
                            </svg>
                        <?php elseif (in_array($widget->type, ['TXT', 'RTF', 'LOG'])) : ?>
                            <svg class="icon icon-txt">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-document"></use>
                            </svg>
                        <?php elseif (in_array($widget->type, ['XLS', 'XLSX'])) : ?>
                            <svg class="icon icon-excel">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-excel"></use>
                            </svg>
                        <?php elseif (in_array($widget->type, ['ZIP', 'RAR'])) : ?>
                            <svg class="icon icon-secondary">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder-zip"></use>
                            </svg>
                        <?php elseif (in_array($widget->type, ['FOLDER'])) : ?>
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

                    <?php if (!empty($widget->dateSyncDrive)) : ?>
                        <svg class="icon icon-xs icon-overlay bg-google-drive icon-padded rounded-circle icon-white" data-toggle="tooltip" data-html="true" title="<?= $lastSyncDrive ?>">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#google-drive"></use>
                        </svg>
                    <?php endif ?>

                    <?php if (!empty($widget->link_document) && ($widget->type != 'FOLDER')) : ?>
                        <span class="text mr-1"><?= Module::t('amosdesign', 'LINK ESTERNO') ?></span>
                    <?php elseif (($widget->type == 'FOLDER')) : ?>
                        <span class="text mr-1"><?= Module::t('amosdesign', 'CARTELLA') ?></span>
                    <?php else : ?>
                        <span class="text mr-1"><?= $widget->type ?></span>
                    <?php endif ?>

                    <?php
                    if (!empty($widget->size)) :
                        if ($widget->size > 1023) {
                            $textSize = round(($widget->size / 1024), 2).' MB';
                        } else {
                            $textSize = $widget->size.' KB';
                        }
                        ?>
                        <span class="text text-capitalize">(<?= $textSize ?>)</span>
                    <?php endif ?>

                    <div class="info-doc-top-right ml-auto d-flex align-items-center">

                        <?php if (!empty($widget->allegatiNum)) : ?>
                            <div class="allegatiNum">
                                <svg class="icon icon-sm icon-secondary" data-toggle="tooltip" title="<?= $widget->allegatiNum ?>">
                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#paperclip"></use>
                                </svg>
                            </div>

                        <?php endif; ?>
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                            [
                            'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($widget->model,
                                $widget->actionModify, $widget->actionDelete),
                                'model' => $model
                            ]
                        );
                        ?>

                    </div>

                </div>
                <?php
                if (!empty($widget->actionView)) {
                    ?>
                    <a href="<?= $widget->actionView ?>" class="link-list-title" title="<?=
                    Module::t('amosdesign', 'Dettaglio documento')
                    ?> <?= $widget->title ?>">
                        <h6 class="card-title mb-2 title-three-line"><?= $widget->title ?></h6>
                    </a>

                    <?php
                }
                if (!empty($widget->newPubblication)) :
                    ?>

                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                    );
                    ?>
                <?php endif; ?>
                <?php if (!empty($widget->versionFile)) : ?>
                    <div class="blockquote-footer"><cite title="versione file"><?= Module::t('amosdesign', 'versione') ?> <?= $widget->versionFile ?></cite></div>

                <?php endif ?>
                <!-- <div class="card-text text-sans-serif">< ?= $infoDoc ?></div> -->

                <?php if ($widget->type == 'FOLDER') : ?>
                    <?=
                    \open20\amos\core\helpers\Html::beginTag('a',
                        array_merge($widget->fileUrl, ['class' => 'read-more']))
                    ?>  <?= Module::t('amosdesign', 'Apri cartella') ?> <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                <?php elseif (!empty($widget->link_document) && ($widget->type != 'FOLDER')) : ?>
                    <?=
                    \open20\amos\core\helpers\Html::beginTag('a',
                        array_merge($widget->fileUrl, ['class' => 'read-more']))
                    ?>  <?= Module::t('amosdesign', 'Apri file')
                    ?> <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                <?php else : ?>
                    <?=
                    \open20\amos\core\helpers\Html::beginTag('a',
                        array_merge($widget->fileUrl,
                            ['class' => 'read-more', 'data-toggle' => 'tooltip', 'title' => Module::t('amosdesign',
                            'Scarica il documento principale').' '.$widget->fileName]))
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