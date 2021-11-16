<?php

use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

//https://www.php.net/manual/en/datetime.format.php
$dateLastSyncDrive = DateUtility::getDate($dateSyncDrive, 'php:d/m/Y');
$hourLastSyncDrive = DateUtility::getDateHour($dateSyncDrive, 'php:H:i');


$type = strtoupper($type);
$date = DateUtility::getDate($date);
$customTooltipInfo = (isset($customTooltipInfo)) ? $customTooltipInfo . ' (' . $type . ' - ' . $size . ')' : '';
$lastSyncDrive = (isset($dateSyncDrive)) ? 'Documento Google Drive<br>aggiornato il: ' . $dateLastSyncDrive . ' alle ' . $hourLastSyncDrive  : false;
$infoDoc = '<strong>Nome file principale:</strong>' . ' ' . $nameFile . '<br>';
$infoDoc = (isset($nameSurname)) ? $infoDoc .'<strong>Pubblicato da</strong>' . ' ' . $nameSurname . ' ' . 'il' . ' ' . $date . '<br>' : $infoDoc;
$infoDoc = (isset($category)) ? $infoDoc . '<strong> nella categoria:</strong>' . ' ' . $category  . '<br>' : $infoDoc ;
$infoDoc = (isset($community)) ? $infoDoc . ' ' . '<strong> in community:</strong>' . ' ' . $community : $infoDoc;
$widthColumn = (isset($widthColumn)) ? $widthColumn :  'col-md-4 col-sm-6';
$allegatiNum = (isset($allegatiNum)) ?  'Allegati interni: ' . $allegatiNum  : '';

?>
<div class="<?= $widthColumn ?>">
  <div class="card-wrapper card-space documenti-card-wrapper pb-4 <?= ($typeFolder) ? 'card-type-folder' :  ''; ?>">
    <div class="card card-bg">
      <div class="card-body">
        <div class="categoryicon-top">
          
            <?php if ((in_array(strtolower($type), ['jpg', 'png', 'jpeg', 'svg']))) : ?>
              <svg class="icon icon-image">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-image"></use>
              </svg>
            <?php elseif ((in_array(strtolower($type), ['pdf']))) : ?>
              <svg class="icon icon-pdf">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-pdf"></use>
              </svg>
            <?php elseif ((in_array(strtolower($type), ['doc', 'docx']))) : ?>
              <svg class="icon icon-word">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-word"></use>
              </svg>
            <?php elseif ((in_array(strtolower($type), ['xls', 'xlsx']))) : ?>
              <svg class="icon icon-excel">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-excel"></use>
              </svg>
            <?php elseif ((in_array(strtolower($type), ['zip', 'rar']))) : ?>
              <svg class="icon icon-secondary">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder-zip"></use>
              </svg>
            <?php elseif ((in_array(strtolower($typeFolder), ['folder']))) : ?>
              <svg class="icon icon-folder">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#folder"></use>
              </svg>
            <?php else : ?>
              <svg class="icon icon-secondary">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#file-link"></use>
              </svg>
            <?php endif ?>
          
          <?php if ($dateSyncDrive) : ?>
            <svg class="icon icon-xs icon-overlay bg-google-drive icon-padded rounded-circle icon-white" data-toggle="tooltip" data-html="true" title="<?= $lastSyncDrive ?>">
              <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#google-drive"></use>
            </svg>
          <?php endif ?>

          <?php if (!isset($size) && (!isset($typeFolder))) : ?>
            <span class="text mr-1">LINK ESTERNO</span>
          <?php elseif (!isset($size) && (isset($typeFolder))) : ?>
            <span class="text mr-1">CARTELLA</span>
          <?php else : ?>
            <span class="text mr-1"><?= $type ?></span>
          <?php endif ?>

          <?php if (isset($size)) : ?>
            <span class="text text-capitalize">(<?= $size ?>Kb)</span>
          <?php endif ?>




          <div class="info-doc-top-right ml-auto d-flex align-items-center">
            <!-- < ?= ContextMenuWidget::widget([
              'model' => $model,
              'layout' => '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget.php',

              // 'actionModify' => "/news/news/update?id=" . $model->id,
              // 'actionDelete' => "/news/news/delete?id=" . $model->id,
              // 'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa notizia?'),
              // 'modelValidatePermission' => 'NewsValidate'
            ]) ?> -->
            <?php if ($allegatiNum) : ?>
              <div class="allegatiNum">
                <svg class="icon icon-sm icon-secondary" data-toggle="tooltip" title="<?= $allegatiNum ?>">
                  <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#paperclip"></use>
                </svg>
              </div>

            <?php endif; ?>
            <?php
            echo $this->render(
              '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget'
            );
            ?>

          </div>


        </div>

        <a href="<?= $url ?>" class="link-list-title" title="Dettaglio documento <?= $title ?>">
          <h6 class="card-title mb-2 title-three-line"><?= $title ?></h6>
        </a>



        <?php if (($newPubblication)) : ?>

          <?php
          echo $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
          );
          ?>

        <?php endif; ?>



        <?php if (isset($versionFile)) : ?>
          <div class="blockquote-footer"><cite title="versione file">versione <?= $versionFile ?></cite></div>

        <?php endif ?>
        <!-- <div class="card-text text-sans-serif">< ?= $infoDoc ?></div> -->


        <?php if (isset($typeFolder)) : ?>
          <a class="read-more" href="<?= $url ?>">Apri cartella</a>
        <?php elseif (!isset($size) && (!isset($typeFolder))) : ?>
          <a class="read-more" href="<?= $url ?>">Apri file </a>
        <?php else : ?>
          <a href="<?= $fileUrl ?>" title="Scarica il documento principale: <?= $fileName ?>" data-toggle="tooltip" class="read-more" download>
            Scarica file
          </a>
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