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

<div class="collapse-div collapse-lg border border-light rounded" role="tablist">
  <div class="collapse-header lightgrey-bg-c1 px-4 py-3" id="heading1">
    <a data-toggle="collapse" role="button" href="#collapse1" aria-expanded="false" aria-controls="collapse1" class="btn btn-lg border-0 p-0">
      <div class="d-flex flex-wrap align-items-center">
        <img src="https://picsum.photos/40/40?random=2" class="mb-1 pr-3" alt="icona telefono">
        <div class="h5"><?= Module::t('amosdesign', 'Modalità di contatto') ?></div>
      </div>

      <div class="tertiary-color font-weight-normal"><?= Module::t('amosdesign', 'Gestisci i canali di contatto tramite i quali puoi ricevere comunicazioni come ') ?><strong><?= Module::t('amosdesign', 'Cittadino') ?></strong></div>
    </a>
  </div>
  <div id="collapse1" class="collapse" role="tabpanel" aria-labelledby="heading1">
    <div class="collapse-body px-4 py-5">
      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.
    </div>
  </div>
</div>