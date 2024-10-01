<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

$columnWidth = (isset($columnWidth)) ? $columnWidth : 'col-12';
$siteBg = (isset($siteBg)) ? $siteBg : 'primary';

?>

<div class="<?= $additionalContainerClass ?> agid-theme-site-container">
    <div class="card card-teaser card-bg-<?= $siteBg ?> rounded <?= $columnWidth ?>">
        <div class="avatar size-lg mr-3">
            <img src="<?= $siteImage ?>" alt="Immagine">
        </div>
        <div class="card-body">
            <h5 class="card-title">
                <?= $siteTitle ?>
            </h5>
            <p class="card-text text-sans-serif">
                <?= $siteDescription ?>
            </p>
        </div>
    </div>
</div>