<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);


?>



<div class="d-flex">
  <?php foreach ( $videos as $video) : ?>
    <div class="<?= $video[width] ?>">
      <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" title="Video YouTube" src="<?= $video[videoSrc] ?>" allowfullscreen></iframe>
      </div>
    </div>             
                      
                      
  <?php endforeach; ?>

</div>
