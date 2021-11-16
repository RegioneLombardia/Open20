<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

?>

<div class="<?= $columnWidth ?> agid-topic-detail-container card card-teaser card-teaser-<?= $cardLateralColor ?> rounded shadow">
    <div class="card-body">
        <h5 class="card-title"><?= $cardTitle ?> </h5>
        <div class="card-text">
            <p><?= $cardSubtitle ?></p>
        </div>
    </div>
    <div class="avatar size-xl">
        <img src="<?= $cardImg ?>" alt="Immagine per <?= $cardTitle ?>">
    </div>
</div>
