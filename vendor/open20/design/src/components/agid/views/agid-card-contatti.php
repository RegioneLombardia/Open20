<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

?>


<div class="agid-card-contatti-container card card-teaser shadow mt-3 rounded <?= $columnWidth ?>">
        <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-telephone"></use>
        </svg>
        <div class="card-body">
            <h5 class="card-title">
                <?= $cardTitle ?>
            </h5>
            <div class="card-text">
                <p><?= $cardSubtitle ?></p>
                <p class="mt-3">T <?= $cardContactTelephone ?></p>
                <p><a href="#"><?= $cardContactSite ?></a></p>
                <p><a href="#"><?= $cardContactEmail ?></a></p>
            </div>
        </div>
</div>