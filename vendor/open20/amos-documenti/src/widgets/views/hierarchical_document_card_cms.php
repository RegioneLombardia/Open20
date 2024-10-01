<?php

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiWidgetAsset;
use open20\amos\documenti\widgets\ItemDocumentCardWidget;
use open20\design\Module;
use open20\design\utility\DateUtility;

/**
 * @var ItemDocumentCardWidget $widget
 */

$documentiAsset = ModuleDocumentiWidgetAsset::register($this);

$widgetDocumentModel = $widget->getModel();

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
$widget->customTooltipInfo = (!empty($widget->customTooltipInfo)) ? $widget->customTooltipInfo . ' (' . $widget->type . ' - ' . $widget->size . ')' : '';

$infoDoc = (!empty($widget->nameSurname)) ? '<strong>' . AmosDocumenti::t('amosdocumenti', 'Published by') . '</strong>' . ' ' . $widget->nameSurname . ' ' . (!empty($widget->date) ? AmosDocumenti::t('amosdocumenti', 'il') . ' ' . $date : '') . '<br>' : '';
$fileName = $widget->getFileName();
$linkDocument = $widget->getLink_document();
if (!empty($fileName)) {
    $infoDoc .= '<strong>' . AmosDocumenti::t('amosdocumenti', '#main_file_name') . '</strong> ' . $fileName;
} elseif (!empty($linkDocument)) {
    $infoDoc .= '<strong>' . $widgetDocumentModel->getAttributeLabel('link_document') . '</strong> ' . $linkDocument;
}
$infoDoc = (!empty($widget->category)) ? $infoDoc . '<strong>' . AmosDocumenti::t('amosdocumenti', '#in_category') . '</strong>' . ' ' . $widget->category . '<br>' : $infoDoc;
$infoDoc = (!empty($widget->community)) ? $infoDoc . ' ' . '<strong>' . AmosDocumenti::t('amosdocumenti', '#in_community') . '</strong>' . ' ' . $widget->community : $infoDoc;
$widget->widthColumn = (!empty($widget->widthColumn)) ? $widget->widthColumn : 'col-md-4 col-sm-6';
$widget->allegatiNum = (!empty($widget->allegatiNum)) ? AmosDocumenti::t('amosdocumenti', '#internal_attachments') . ' ' . $widget->allegatiNum : '';

$widget->actionModify = (!empty($widget->actionModify) ? $widget->actionModify : null);
$widget->actionDelete = (!empty($widget->actionDelete) ? $widget->actionDelete : null);
?>
<div>
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
                            <span class="icon icon-image icon-sm mdi mdi-file-image mr-1"></span>
                        <?php elseif (in_array($widget->type, ['PDF'])) : ?>
                            <span class="icon icon-pdf icon-sm mdi mdi-file-pdf mr-1"></span>
                        <?php elseif (in_array($widget->type, ['DOC', 'DOCX'])) : ?>
                            <span class="icon icon-word icon-sm mdi mdi-file-word mr-1"></span>
                        <?php elseif (in_array($widget->type, ['TXT', 'RTF', 'LOG'])) : ?>
                            <span class="icon icon-txt icon-sm mdi mdi-file-document mr-1"></span>
                        <?php elseif (in_array($widget->type, ['XLS', 'XLSX'])) : ?>
                            <span class="icon icon-excel icon-sm mdi mdi-file-excel mr-1"></span>
                        <?php elseif (in_array($widget->type, ['ZIP', 'RAR'])) : ?>
                            <span class="icon icon-secondary icon-sm mdi mdi-folder-zip mr-1"></span>
                        <?php elseif (in_array($widget->type, ['FOLDER'])) : ?>
                            <span class="icon icon-folder icon-sm mdi mdi-folder mr-1"></span>
                        <?php elseif ((in_array($widget->type, ['CSV']))) : ?>
                            <span class="icon icon-black icon-sm mdi mdi-file-delimited"></span>
                        <?php elseif ((in_array($widget->type, ['PPTX']))) : ?>
                            <span class="icon icon-powerpoint icon-sm mdi mdi-file-powerpoint"></span>
                        <?php else : ?>
                            <span class="icon icon-secondary icon-sm mdi mdi-file-link mr-1"></span>
                        <?php
                        endif;
                    } else {
                        ?>
                            <span class="icon icon-secondary icon-sm mdi mdi-file-link mr-1"></span>
                    <?php }
                    ?>

                    <?php if (!empty($widget->dateSyncDrive)) : ?>
                        <svg class="icon icon-xs icon-overlay bg-google-drive icon-padded rounded-circle icon-white" data-toggle="tooltip" data-html="true" title="<?= $lastSyncDrive ?>">
                        <use xlink:href="<?= $documentiAsset->baseUrl ?>/sprite/material-sprite.svg#google-drive"></use>
                        </svg>
                    <?php endif ?>

                    <?php if (empty($widget->size) && ($widget->type != 'FOLDER')) : ?> <!-- widget size -->
                        <span class="text mr-1"><?= Module::t('amosdesign', 'LINK ESTERNO') ?></span>
                    <?php elseif (($widget->type == 'FOLDER')) : ?>
                        <span class="text mr-1"><?= Module::t('amosdesign', 'CARTELLA') ?></span>
                    <?php else : ?>
                        <span class="text mr-1"><?= $widget->type ?></span>
                    <?php endif ?>

                    <?php
                    if (!empty($widget->size)) :
                        ?>
                        <span class="text text-capitalize">(<?= $widget->size ?>)</span>
                    <?php endif ?>

                    <div class="info-doc-top-right ml-auto d-flex align-items-center">

                        <?php if (!empty($widget->allegatiNum)) : ?>
                            <div class="allegatiNum">
                                <span data-toggle="tooltip" title="<?= $widget->allegatiNum ?>" class="icon icon-secondary icon-sm mdi mdi-paperclip mr-1"></span>
                            </div>

                        <?php
                        endif;

                        if ($widget->type == 'FOLDER') {
                            $confirmDeleteMessage = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
                        } else {
                            $confirmDeleteMessage = AmosDocumenti::t('amosdocumenti', '#confirm_delete_document');
                        }

                        ?>
                        <?= ContextMenuWidget::widget([
                            'model' => $widgetDocumentModel,
                            'actionModify' => $widget->actionModify,
                            'actionDelete' => "/documenti/documenti/delete?id=" . $widgetDocumentModel->id,
                            'modelValidatePermission' => 'DocumentValidate',
                            'labelDeleteConfirm' => $confirmDeleteMessage
                            //                    'mainDivClasses' => 'col-xs-1 nop'
                        ]) ?>

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
                        '@vendor/open20/amos-documenti/src/widgets/views/badge-new-publication'
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
                    <span class="icon icon-info icon-sm mdi mdi-information-outline mr-1"></span>
                </a>
            </div>
        </div>
    </div>
</div>
