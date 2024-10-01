<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

?>


<div class="<?= $columnWidth ?> agid-card-place-container card card-teaser shadow mt-3 rounded">
    <svg class="icon">
        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#pin"></use>    
    </svg>
    <div class="card-body">
        <h5 class="card-title">
            <?= $cardCity ?>
        </h5>
        <div class="card-text">
            <p><?= $cardAdress ?> - <?= $cardCAP ?></p>
            <a href="<?= $moreDetailUrl ?>" title="<?= $moreDetailTitle ?>" class="mt-3"><?= $moreDetailText ?></a>
        </div>
    </div>  
</div>