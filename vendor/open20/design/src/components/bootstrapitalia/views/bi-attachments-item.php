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
use open20\design\utility\DesignUtility;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$infoDoc = '<strong>' . Module::t('amosdesign', 'Pubblicata da') . '</strong>' . ' ' . $nameSurname . ' ' . '<strong>' . Module::t('amosdesign', 'il') . '</strong>' . ' ' . $date;
$infoDoc = (isset($category)) ? $infoDoc . ' ' . '<strong>' . Module::t('amosdesign', 'in') . '</strong>' . ' ' . $category : $infoDoc;
$infoDoc = (isset($community)) ? $infoDoc . ' ' . '<strong>' . Module::t('amosdesign', 'per') . '</strong>' . ' ' . $community : $infoDoc;
$lastSyncDrive = (isset($dateSyncDrive)) ? Module::t('amosdesign', 'Documento Google Drive').'<br>'. Module::t('amosdesign', 'aggiornato il') . ' ' . ":" . ' ' . $dateLastSyncDrive . Module::t('amosdesign', 'alle') . ' ' . $hourLastSyncDrive  : false;

?>

<div class="document-item-container py-2">
  <div>
    <div class="mb-2">

      <div class="info-file d-flex align-items-baseline">
        <div>
        <?php if ((in_array(strtolower($type), ['jpg', 'png', 'jpeg', 'svg']))) : ?>
          <svg class="icon icon-image icon-sm">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#image"></use>
          </svg>
        <?php elseif ((in_array(strtolower($type), ['pdf']))) : ?>
          <svg class="icon icon-pdf icon-sm">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-pdf-box"></use>
          </svg>
        <?php elseif ((in_array(strtolower($type), ['doc', 'docx']))) : ?>
          <svg class="icon icon-word icon-sm">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-word-box"></use>
          </svg>
        <?php elseif ((in_array(strtolower($type), ['xls', 'xlsx']))) : ?>
          <svg class="icon icon-excel icon-sm">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-excel-box"></use>
          </svg>
        <?php elseif ((in_array(strtolower($type), ['zip', 'rar']))) : ?>
          <svg class="icon icon-secondary icon-sm">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder-zip"></use>
          </svg>
        <?php elseif ((in_array(strtolower($typeFolder), ['folder']))) : ?>
          <svg class="icon icon-folder icon-sm">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder"></use>
          </svg>
        <?php else : ?>
          <svg class="icon icon-secondary icon-sm">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#link-box"></use>
          </svg>
        <?php endif ?>

        <?php if ($dateSyncDrive) : ?>

          <svg class="icon icon-xs icon-overlay bg-google-drive icon-padded rounded-circle icon-white" data-toggle="tooltip" data-html="true" title="<?= $lastSyncDrive ?>">
            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#google-drive"></use>
          </svg>
        <?php endif ?>
        </div>
        <div class="overflow-hidden">
        <a class="ml-2 filename" href="<?= $fileUrl ?>" title="Scarica <?= $fileName ?>">
          <span class="mb-0 font-weight-bold filename__base"><?= $fileName ?></span>
          <span class="mb-0 font-weight-bold filename__extension">.<?= $type ?></span>
        </a>
        </div>
        <?php if (isset($size)) : ?>
          <div class="ml-2 small text-muted d-flex"><span class="text-uppercase mr-1"><?= $type ?></span><span class="ml-1">(<?= DesignUtility::bytesFormat($size) ?>)</span></div>
        <?php endif ?>
      </div>
    </div>

  </div>

</div>