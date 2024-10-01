<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

?>

<li>
    <a href="<?= $url ?>" title="<?= Module::t('amosdesign', 'Visualizza la pagina') . ' ' . $title ?>">
        <div class="it-right-zone">
      <span class="text">
           <?php if (!empty($publicationDate)) { ?>

                    <em><?= Module::t('amosdesign',"Pubblicato il").' '. $publicationDate ?></em>

           <?php } ?>
          <span class="badge badge-secondary mr-1">
          <?= $type ?>
        </span>
          <?= strip_tags($title) ?>
          <em>
          <?= $description ?>
        </em>
      </span>
            <span class="it-multiple">
        <svg class="icon icon-primary">
          <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#arrow-right"></use>
        </svg>
      </span>
        </div>
    </a>
</li>