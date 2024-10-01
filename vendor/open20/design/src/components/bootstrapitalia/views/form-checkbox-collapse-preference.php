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

?>

<div class="collapse-div collapse-lg shadow-sm border-0 rounded px-4 py-4" role="tablist">
  <div class="collapse-header" id="heading2">
    <div role="contentinfo" class="form-check m-0 border-0 p-0" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
      <div class="d-flex align-items-center">
        <input type="checkbox" class="mr-2" id="customCheck" name="example1">
        <label class="custom-control-label lead color-primary mb-0 border-0 py-0 h-auto" for="customCheck">
          <span class="h5 font-weight-normal primary-color"><?= Module::t('amosdesign', 'Sono interessato a informazioni per')?> <strong><?= Module::t('amosdesign', 'Cittadini')?></strong></span>
        </label>
      </div>
    </div>

  </div>
  <div id="collapse2" class="collapse" role="tabpanel" aria-labelledby="heading2">
    <div class="collapse-body mt-4 pt-4 pb-0 border-top tertiary-color">
      <p ><strong><?= Module::t('amosdesign', 'Dove vuoi essere contattato') . '?'?></strong><br>
      <?= Module::t('amosdesign', 'Per poter ricevere le comunicazioni di tuo interesse è necessario compilare le modalità di contatto')?>
        </p>
    </div>
  </div>
</div>