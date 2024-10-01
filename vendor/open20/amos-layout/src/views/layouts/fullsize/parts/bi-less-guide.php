<?php

use open20\amos\layout\Module;
?>

<div class="bi-guide z-index-1031">
  <a class="d-flex align-items-center justify-content-center border border-primary" data-toggle="modal" data-toggle-second="tooltip" title="<?= Module::t('amoslayout', 'Apri la guida della piattaforma {platformName}', ['platformName' => \Yii::$app->name]) ?>" data-target="#helpGuideModalAmosLayout">
    <span class="mdi mdi-lifebuoy"></span>
    <span class="sr-only"><?= Module::t('amoslayout', 'Apri la guida della piattaforma {platformName}', ['platformName' => \Yii::$app->name]) ?></span>
  </a>
</div>

<div class="modal it-dialog-scrollable fade" tabindex="-1" role="dialog" id="helpGuideModalAmosLayout">
  <div class="modal-dialog modal-dialog-right modal-xl" role="document">
    <div class="modal-content text-left">
      <div class="modal-header shadow-sm">
        <h2 class="h3 modal-title"><?= Module::t('amoslayout', 'Guida {platformName}', ['platformName' => \Yii::$app->name]) ?></h2>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span class="mdi mdi-close"></span>
        </button>
      </div>
      <div class="modal-body pt-3">
        <div id="helpGuidaGoTopAmosLayout" class="d-none"></div>
        <?= $this->render($pathView) ?>
      </div>
    </div>
    <div class="modal-footer justify-content-start border-top">
      <a href="javascript:void(0)" data-jsanchortarget="#helpGuidaGoTopAmosLayout"><?= Module::t('amoslayout', 'Torna su') ?></a>
    </div>
  </div>
</div>