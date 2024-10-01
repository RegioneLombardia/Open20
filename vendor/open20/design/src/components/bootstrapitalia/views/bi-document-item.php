<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$date  = (isset($date)) ? \Yii::$app->formatter->asDate($date, 'php:d mm yy') : '';

$enableInfoDoc = (isset($enableInfoDoc) ? $enableInfoDoc : true);
$enableDetailDoc = (isset($enableDetailDoc) ? $enableDetailDoc : true);

if ($enableInfoDoc && !isset($infoDoc)) {
  $infoDoc = '<strong>' . Module::t('amosdesign', 'Pubblicata da') . '</strong>' . ' ' . $nameSurname;
  $infoDoc = (!empty($date)) ? $infoDoc . ' ' . '<strong>' . Module::t('amosdesign', 'il') . '</strong>' . ' ' . $date . ' ' : $infoDoc;
  $infoDoc = (isset($category)) ? $infoDoc . ' ' . '<strong>' . Module::t('amosdesign', 'Categoria:') . '</strong>' . ' ' . $category : $infoDoc;
  $infoDoc = (isset($community)) ? $infoDoc . ' ' . '<strong>' . Module::t('amosdesign', 'per') . '</strong>' . ' ' . $community : $infoDoc;
}

$lastSyncDrive = (isset($dateSyncDrive)) ?  Module::t('amosdesign', 'Documento Google Drive') . '<br>' . Module::t('amosdesign', 'aggiornato il:') . $dateLastSyncDrive . Module::t('amosdesign', 'alle') . $hourLastSyncDrive  : false;
$widthColumn = (isset($widthColumn)) ? $widthColumn :  'col-12';

$buttons   = (isset($buttons) ? $buttons : []);
$model     = (isset($model) ? $model : null);
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);
$enableFileName = (isset($enableFileName) ? $enableFileName : true);
?>

<div class="<?= $widthColumn ?>">
  <div class="document-item-container border-bottom py-4">
    <div>
      <div class="d-flex align-items-start mb-2">

        <div class="info-file d-flex text-secondary small flex-wrap">

          <?php if ((in_array(strtolower($type), ['jpg', 'png', 'jpeg', 'svg']))) : ?>
            <svg class="icon icon-image icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-image"></use>
            </svg>
          <?php elseif ((in_array(strtolower($type), ['pdf']))) : ?>
            <svg class="icon icon-pdf icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-pdf"></use>
            </svg>
          <?php elseif ((in_array(strtolower($type), ['doc', 'docx']))) : ?>
            <svg class="icon icon-word icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-word"></use>
            </svg>
          <?php elseif ((in_array(strtolower($type), ['xls', 'xlsx']))) : ?>
            <svg class="icon icon-excel icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-excel"></use>
            </svg>
          <?php elseif ((in_array(strtolower($type), ['zip', 'rar']))) : ?>
            <svg class="icon icon-secondary icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder-zip"></use>
            </svg>
          <?php elseif ((in_array(strtolower($type), ['txt']))) : ?>
            <svg class="icon icon-txt icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-document"></use>
            </svg>
          <?php elseif ($typeFolder) : ?>
            <svg class="icon icon-folder icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder"></use>
            </svg>
          <?php else : ?>
            <svg class="icon icon-secondary icon-sm">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-link"></use>
            </svg>
          <?php endif ?>

          <?php if ($dateSyncDrive) : ?>
            <svg class="icon icon-xs icon-overlay bg-google-drive icon-padded rounded-circle icon-white" data-toggle="tooltip" data-html="true" title="<?= $lastSyncDrive ?>">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#google-drive"></use>
            </svg>
          <?php endif ?>
          <?php if ($type == 'txt') : ?>
            <span class="text text-black mx-1 text-uppercase"><?= Module::t('amosdesign', 'Documento Testuale') ?></span>
          <?php elseif (isset($size) && $size == 0 && (!$typeFolder)) : ?>
            <span class="text mx-1 text-uppercase"><?= Module::t('amosdesign', 'Link esterno') ?></span>
          <?php elseif (isset($size) && $size == 0 && ($typeFolder)) : ?>
            <span class="text mx-1 text-uppercase"><?= Module::t('amosdesign', 'Cartella') ?></span>
          <?php else : ?>
            <span class="text mx-1 text-uppercase"><?= $type ?></span>
          <?php endif ?>

          <?php if (isset($size) && $size > 0 && (!$typeFolder)) : ?>
            <span class="text text-capitalize"><?= '(' . $size . 'Kb)' ?></span>
            <?php if($enableFileName): ?>
              <span class="text ml-2"><?= '-' . ' ' . Module::t('amosdesign', 'Nome file principale') . ':' . ' ' ?>
                <?= ((strlen($fileName) > 80) ? substr($fileName, 0, 75) . '[...]' : $fileName) . '.' . $type ?>
              </span>
            <?php endif ?>

          <?php endif ?>
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
                'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete),
                'model' => $model
              ]
            );
            ?>
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <?php if ($typeFolder) : ?>
          <a class="link-list-title " href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Vai alla cartella') ?> <?= $title ?>">
            <h3 class="h5 mb-0 font-weight-bold"><?= $title ?></h3>
          </a>
        <?php else : ?>
          <a class="link-list-title " href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Vai al documento') ?> <?= $title ?>">
            <h3 class="h5 mb-0 font-weight-bold"><?= $title ?></h3>
          </a>
        <?php endif; ?>
        <?php if (!empty($contentScopesAvatar)) : ?>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="<?= $contentScopesAvatar ?>">
                            <svg class="icon icon-sm icon-primary" role="img" aria-label="News in community">
                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#account-supervisor-circle"></use>
                            </svg>
                            <span class="sr-only"><?= $contentScopesAvatar ?></span>
                        </a>
          <?php endif; ?>
      </div>
      
      <?php if (isset($description)) : ?>
        <p class="text-secondary mb-2"><?= $description ?></p>
      <?php endif ?>
      <?php if($enableInfoDoc): ?>
        <div class="small mb-2">
          <?= $infoDoc ?>
        </div>
      <?php endif; ?>
      <div>
          <?php if ($typeFolder) : ?>
            <a href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Apri la cartella') ?> <?= $fileName ?>" class="read-more d-inline mr-2"><?= Module::t('amosdesign', 'Apri') ?></a>
          <?php else : ?>

              <?php if (isset($size) && $size == 0 && (!$typeFolder)) : ?>
                <a href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Vedi il dettaglio del documento esterno') ?> <?= $fileName ?>" class="read-more d-inline mr-2"><?= Module::t('amosdesign', 'Dettaglio') ?></a>

              <?php else : ?>
                <a href="<?= $fileUrl ?>" title="<?= Module::t('amosdesign', 'Scarica il documento') ?> <?= $fileName ?>" class="read-more d-inline mr-2" download><?= Module::t('amosdesign', 'Scarica') ?></a>
                <?php if($enableDetailDoc): ?>
                  <a href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Vedi il dettaglio del documento') ?> <?= $fileName ?>" class="read-more d-inline mr-2"><?= Module::t('amosdesign', 'Dettaglio') ?></a>
                <?php endif; ?>
              <?php endif; ?>

          <?php endif; ?>
      </div>
    </div>

  </div>
</div>