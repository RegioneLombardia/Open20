<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

?>


<div class="<?= $columnWidth ?> agid-card-document-container card card-teaser shadow mt-3 rounded">
    <svg class="icon icon-<?= $documentIconColor ?>">
        <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#file-document-outline"></use>
    </svg>
    <div class="card-body">
        <h5 class="card-title">
            <a title="<?= $documentUrlTitle ?>" href="<?= $documentUrl ?>"><?= $documentTitle ?></a>
        </h5>
    </div>       
</div>

