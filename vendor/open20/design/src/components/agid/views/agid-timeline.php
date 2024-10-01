<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

$dataInizioGiorno = (isset($dataInizioGiorno)) ? $dataInizioGiorno : 'oggi';

?>

<div class="agid-timeline-container point-list-wrapper my-4">
    <div class="point-list">
        <div class="point-list-aside">
            <div class="point-date text-monospace"><?= $dataInizioGiorno ?></div>
            <div class="point-month text-monospace"><?= $dataInizioMese ?></div>
        </div>
        <div class="point-list-content">
            <div class="card card-teaser shadow rounded">
                <div class="card-body">
                    <h5 class="card-title">
                      <?= $inizioLabel ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="point-list">
        <div class="point-list-aside">
            <div class="point-date text-monospace"><?= $dataFineGiorno ?></div>
            <div class="point-month text-monospace"><?= $dataFineMese ?></div>
        </div>
        <div class="point-list-content">
            <div class="card card-teaser shadow rounded">
                <div class="card-body">
                    <h5 class="card-title">
                    <?= $fineLabel ?>
                    </h5>
                </div>
            </div>
    </div>
</div>
          


</div>