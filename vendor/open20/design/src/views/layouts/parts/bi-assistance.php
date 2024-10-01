<?php

use open20\design\Module;
use Yii;

/**
 * @var $currentAsset \yii\web\AssetBundle
 * @var $isMail bool
 * @var $mailAddress string
 * @var $urlAssistance string
 */
if(isset(Yii::$app->params['assistance']) && isset(Yii::$app->params['assistance']['roles'])) {
    $canRender = false;

    foreach (Yii::$app->params['assistance']['roles'] as $role) {
        if (Yii::$app->user->can($role))
            $canRender = true;
    }

    if(!$canRender) return;
}
?>

  <div class="bi-assistance z-index-1031">
    <a class="d-flex align-items-center justify-content-center border border-primary" href="<?= $isMail ? 'mailto:' . $mailAddress :  $urlAssistance ?>" data-toggle="tooltip" data-placement="left" data-original-title="<?= Module::t('amosdesign', 'Hai bisogno di assistenza?') ?>" target="_blank">
      <svg class="icon icon-sm">
        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#help"></use>
      </svg>
      <span class="sr-only"><?= Module::t('amosdesign', 'Hai bisogno di assistenza? Clicca qui') ?></span>
    </a>
  </div>
