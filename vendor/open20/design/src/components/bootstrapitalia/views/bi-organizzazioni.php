<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$labelLinkCta = (isset($labelLinkCta)) ? $labelLinkCta : Module::t('amosdesign', 'Esplora');
$titleLinkCtaCta = (isset($titleLinkCta)) ? $titleLinkCta : Module::t('amosdesign', 'Vai alla pagina di') . ' ' . $title;

$widthColumn = (isset($widthColumn)) ? $widthColumn :  'col-lg-4 col-md-6';

$hideCta = (isset($hideCta)) ? $hideCta : false;
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);
$model     = (isset($model) ? $model : null);
if (isset($hideAllCtaGuest) && $hideAllCtaGuest) {
  $hideAllCtaGuest = \Yii::$app->user->isGuest;
}
?>


<div class="organizzazioni-wrapper border-light border-bottom py-3 <?= $widthColumn ?> <?= $additionalClass ?>">
  <div class="row flex-sm-nowrap h-100">
    <div class="col-12 col-md-4">
      <?php if ($hideAllCtaGuest) : ?>
        <img class="img-fluid" src="<?= $image ?>" alt="<?= $title ?>">
      <?php else : ?>
        <a href="<?= $url ?>" class="organizzazioni d-block" title="<?= $titleLinkCta ?>">
          <?php if (isset($image)) : ?>
            <img class="img-fluid" src="<?= $image ?>" alt="<?= $title ?>">
          <?php endif ?>
        </a>
      <?php endif; ?>
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

    <div class="col-12 col-md-8">
      <div class="ml-0 ml-sm-2 mt-2 mt-sm-0 d-flex flex-column align-items-start h-100 <?= $extraTextSize ?> ">
        <?php if (!(isset($hideTitle))) : ?>
          <a href="<?= $url ?>" class="link-list-title mb-1" title="<?= $titleLinkCta ?>">
            <h3 class="h5 font-weight-bold mb-0 title-one-line w-100"><?= $title ?></h3>
          </a>
        <?php endif; ?>
        <?php if (!$hideAllCtaGuest) : ?>
          <?php if (!$hideCta) : ?>
            <a href="<?= $url ?>" class="btn btn-xs btn-primary py-1 mt-auto" title="<?= $titleLinkCta ?>"><?= $labelLinkCta ?></a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>