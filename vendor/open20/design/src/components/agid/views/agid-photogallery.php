<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);


?>



<div class="row">
  <?php foreach ( $images as $image) : ?>
    <div class="<?= $image [width] ?>">
      <div class="it-grid-item-wrapper">
        
          <div class="img-responsive-wrapper">
            <div class="img-responsive">
              <div class="img-wrapper"><img src="<?= $image [imageSrc] ?>" alt="image Alt" title="Image Title"></div>
            </div>
          </div>
          <span class="it-griditem-text-wrapper">
            <span class="it-griditem-text"><?= $image [caption] ?></span>
          </span>
        
      </div>
    </div>             
                      
                      
  <?php endforeach; ?>

  </div>