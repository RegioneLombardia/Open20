<?php
use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$titlelink = 'Vai alla pagina di' . ' ' . $organizzatore;
$tooltipText = '<strong>' . $organizzatore . '</strong>' . (isset($tooltipAdditionalInfo) ? '<br/><em>' . $role . '</em>' : '');  //se esiste un campo role apposta mettere quel campo al posto di addiotional info

?>



  
  
<div class="organizzazioni-wrapper mb-0  <?= $widthColumn ?> <?= $additionalClass ?> <?= $singleElementsWidthFullsize ?> <?= $singleElementsWidthMobile ?>">
  <div class="row flex-sm-nowrap h-100">
    <div class="col-sm-5">
      <a href="<?= $url ?>" class="organizzazioni d-block" <?= isset($showTooltip) ? 'data-toggle="tooltip" data-html="true" ' : '' ?> title="<?= isset($showTooltip) ? $tooltipText : $titlelink ?>">
        <?php if (isset($imageOrganizzatore)) : ?>
          <img class="img-fluid" src="<?= $imageOrganizzatore ?>" alt="<?= $organizzatore ?>">
        <?php endif ?>
      </a>
    </div>  
    <div class="col-sm-7">
      <div class="ml-0 ml-sm-2 mt-2 mt-sm-0 d-flex flex-column align-items-start h-100 <?= $extraTextSize ?> ">
        <?php if (!(isset($hideOrganizzatore))) : ?>
            <a href="<?= $url ?>" class="link-list-title mb-1" title="Vai alla pagina di <?= $organizzatore ?>">
              <h3 class="h5 font-weight-bold mb-0 title-one-line w-100"><?= $organizzatore ?></h3>
            </a>
        <?php endif; ?>
        <?php if (isset($showCtaEsplora)) : ?>
          <a href="<?= $url ?>" class="btn btn-xs btn-primary py-1 mt-auto" title="Vai alla pagina di<?= ' ' . $organizzatore?>">Esplora</a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
 