<?php

use open20\design\assets\BootstrapItaliaDesignAsset;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$landscapeColsNumber = (isset($landscapeColsNumber)) ? $landscapeColsNumber : 'four';

?>
<div class="it-carousel-wrapper it-carousel-landscape-abstract-<?= $landscapeColsNumber ?>-cols owl-carousel-design list-view-owl-carousel <?= $additionalClass ?>">
    <div class="it-carousel-all owl-carousel">
        {items}
    </div>
</div>