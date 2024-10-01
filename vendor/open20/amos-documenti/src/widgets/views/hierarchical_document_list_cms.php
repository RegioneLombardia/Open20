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
    $dateLastSyncDrive = DateUtility::getDate($widget->dateSyncDrive, 'php:d/m/Y');
    $hourLastSyncDrive = DateUtility::getDateHour($widget->dateSyncDrive, 'php:H:i');
    $lastSyncDrive     = Module::t('amosdesign', 'Documento Google Drive').'<br>'.Module::t('amosdesign',
            'aggiornato il').$dateLastSyncDrive.Module::t('amosdesign', 'alle').$hourLastSyncDrive;
}

$widget->type = strtoupper($widget->type);
if (!empty($widget->date)) {
    $date = DateUtility::getDate($widget->date);
}
$widget->customTooltipInfo = (!empty($widget->customTooltipInfo)) ? $widget->customTooltipInfo . ' (' . $widget->type . ' - ' . $widget->size . ')' : '';

if ($widget->type == 'FOLDER') {
    $infoDoc = (!empty($widget->getNameSurname())) ? '<strong>' . AmosDocumenti::t('amosdocumenti', 'Published by') . '</strong> ' . $widget->getNameSurname() . ' ' . (!empty($widget->date) ? AmosDocumenti::t('amosdocumenti', 'il') . ' ' . $date : '') . '<br>' : '';
} else {
    $fileName = $widget->getFileName();
    $linkDocument = $widget->getLink_document();
    $infoDoc = (!empty($widget->getNameSurname()) ? '<strong>' . AmosDocumenti::t('amosdocumenti', 'Published by') . '</strong> ' . $widget->getNameSurname() . '<br>' : '');
    if (!empty($fileName)) {
        $infoDoc .= '<strong>' . AmosDocumenti::t('amosdocumenti', '#main_file_name') . '</strong> ' . $fileName;
    } elseif (!empty($linkDocument)) {
        $infoDoc .= '<strong>' . $widgetDocumentModel->getAttributeLabel('link_document') . '</strong> ' . $linkDocument;
    }
}

$widget->widthColumn = (!empty($widget->widthColumn)) ? $widget->widthColumn : 'col-md-4 col-sm-6';
$widget->allegatiNum = (!empty($widget->allegatiNum)) ? AmosDocumenti::t('amosdocumenti', '#internal_attachments') . ' ' . $widget->allegatiNum : '';

$widget->actionModify = (!empty($widget->actionModify) ? $widget->actionModify : null);
$widget->actionDelete = (!empty($widget->actionDelete) ? $widget->actionDelete : null);
?>
<div class="col-12">
    <div class="documenti-list-wrapper pb-3 <?=
    ($widget->type == 'FOLDER') ? 'type-folder' : '';
    ?>">
        <div class="list-bg">
            <div class="row">
                <div class="col-md-1 col-xs-12 icon-title-container d-flex align-items-center flex-wrap ">
                    <div class="categoryicon-top mr-2">
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

                        <span class="info-doc-top-right ml-auto d-flex align-items-center">

                            <?php if (!empty($widget->allegatiNum)) : ?>
                                <div class="allegatiNum">
                                    <span data-toggle="tooltip" title="<?= $widget->allegatiNum ?>" class="icon icon-secondary icon-sm mdi mdi-paperclip mr-1"></span>
                                </div>

                            <?php endif; ?>

                        </span>
                    </div>
                </div>
                <div class="col-md-8 col-xs-12">
                    <?php
                    // 12836 sulle cartelle ha un senso che al click ci si possa entrare, andare in view ha un senso solo per il file
                    if (($widget->type == 'FOLDER') && isset($widget->fileUrl['href']) && !empty($widget->fileUrl['href'])) {
                        ?>
                        <a href="<?= $widget->fileUrl['href'] ?>" class="link-list-title" title="<?=
                        Module::t('amosdesign', 'Dettaglio documento')
                        ?> <?= $widget->title ?>">
                            <h6 class="list-title mb-0 title-three-line"><?= $widget->title ?></h6>
                        </a>

                        <?php
                    } else if (!empty($widget->actionView)) {
                        ?>
                        <a href="<?= $widget->actionView ?>" class="link-list-title" title="<?=
                        Module::t('amosdesign', 'Dettaglio documento')
                        ?> <?= $widget->title ?>">
                            <h6 class="list-title mb-0 title-three-line"><?= $widget->title ?></h6>
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
                    <?php if ($widget->type != 'FOLDER') : ?>
                        <div class="documenti-list-info-container d-flex align-items-center flex-wrap small">
                                <span class="font-weight-normal mr-1">
                                     <span class="icon icon-info icon-sm mdi mdi-calendar-clock mr-1"><?= $date; ?></span>
                                     -
                                    <span class="icon icon-info icon-sm mdi mdi-file-swap mr-1 text text-capitalize"><?= $widget->size ?></span>
                                </span>
                                <span class="descrizione-sintetica font-weight-normal mr-1">
                                    <?= $widgetDocumentModel->descrizione_breve; ?>
                                </span>
                        </div>
                    <?php endif ?>
                </div>
                <!-- <div class="card-text text-sans-serif">< ?= $infoDoc ?></div> -->

                <div class="col-md-3 col-xs-12 info-cta-manage-container d-flex align-items-center ml-auto">
                    <a href="javascript:void(0)" data-toggle="tooltip" data-html="true" title="<?= $infoDoc ?>" class="info-link mr-3">
                        <span class="icon icon-info icon-sm mdi mdi-information-outline mr-1"></span>
                    </a>
                    <?php if ($widget->type == 'FOLDER') : ?>
                        <?=
                        \open20\amos\core\helpers\Html::beginTag('a',
                            array_merge($widget->fileUrl, ['class' => 'read-more mr-auto']))
                        ?>  <?= Module::t('amosdesign', 'Apri') ?> <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                    <?php elseif (!empty($widget->link_document) && ($widget->type != 'FOLDER')) : ?>
                        <?=
                        \open20\amos\core\helpers\Html::beginTag('a',
                            array_merge($widget->fileUrl, ['class' => 'read-more mr-auto']))
                        ?>  <?= Module::t('amosdesign', 'Apri')
                        ?> <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                    <?php else : ?>
                        <?=
                        \open20\amos\core\helpers\Html::beginTag('a',
                            array_merge($widget->fileUrl,
                                ['class' => 'read-more mr-auto', 'data-toggle' => 'tooltip', 'title' => Module::t('amosdesign',
                                'Scarica il documento principale').' '.$widget->fileName]))
                        ?>
                        <?= Module::t('amosdesign', 'Scarica') ?>
                        <?= \open20\amos\core\helpers\Html::endTag('a') ?>
                    <?php endif ?>
                    <div class="ml-2">
                        <?php

                        if ($widget->type == 'FOLDER') {
                            $confirmDeleteMessage = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
                        } else {
                            $confirmDeleteMessage = AmosDocumenti::t('amosdocumenti', '#confirm_delete_document');
                        }

                        echo ContextMenuWidget::widget([
                            'model' => $widgetDocumentModel,
                            'actionModify' => $widget->actionModify,
                            'actionDelete' => "/" . AmosDocumenti::getModuleName() . "/documenti/delete?id=" . $widgetDocumentModel->id,
                            'modelValidatePermission' => 'DocumentValidate',
                            'labelDeleteConfirm' => $confirmDeleteMessage
                            //                    'mainDivClasses' => 'col-xs-1 nop'
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
