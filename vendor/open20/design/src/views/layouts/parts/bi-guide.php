<?php

use open20\design\Module;
?> 

<div class="bi-guide z-index-1031">
  <a type="button" class="d-flex align-items-center justify-content-center border border-primar dropdown-toggle" data-toggle="modal" data-toggle-second="tooltip" title="<?= Module::t('amosdesign', 'Apri la guida della piattaforma {platformName}', ['platformName' => \Yii::$app->name]) ?>" data-target="#helpGuideModalDesign">
    <svg class="icon icon-sm">
      <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#lifebuoy"></use>
    </svg>
    <span class="sr-only"><?= Module::t('amosdesign', 'Apri la guida della piattaforma {platformName}', ['platformName' => \Yii::$app->name]) ?></span>
  </a>
</div>

<div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="helpGuideModalDesign">
  <div class="modal-dialog modal-dialog-right modal-xl" role="document">
    <div class="modal-content text-left">
      <div class="modal-header shadow-sm">
        <h2 class="h3 modal-title"><?= Module::t('amosplanner', 'Guida {platformName}', ['platformName' => \Yii::$app->name]) ?></h2>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-close"></use>
          </svg>
        </button>
      </div>
      <div class="modal-body pt-3">
        <div id="helpGuidaGoTopDesign" class="d-none"></div>

        <?= $this->render($pathView) ?>

      </div>
    </div>
    <div class="modal-footer justify-content-start border-top">
      <a href="javascript:void(0)" data-jsanchortarget="#helpGuidaGoTopDesign"><?= Module::t('amosdesign', 'Torna su') ?></a>
    </div>
  </div>
</div>