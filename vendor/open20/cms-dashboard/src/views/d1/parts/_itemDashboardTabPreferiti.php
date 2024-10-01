<?php

use open20\design\assets\BootstrapItaliaDesignAsset;

$currentAsset = BootstrapItaliaDesignAsset::register($this);


if (!empty($model)) {
    if ($model instanceof \open20\amos\news\models\News) {
        $title = $model->titolo;
        $url = $model->getFullViewUrl();
        $modelLabel = strtolower($model->getGrammar()->getModelLabel());
    }
}

$title = 'Titolo del segnalibro';
$url = '#';
$modelLabel = 'Tipo';

?>
<div class="row itemRowDashboardTab">
    <div class="col">
        <span class="title">
            <span class="badge badge-secondary"><?= $modelLabel ?></span>
            <a class="" href="<?= $url ?>" title="<?= 'Visualizza' . ' ' . $title . ' ' . '[Apre in nuova finestra]' ?>" target="_blank"><?= $title ?></a>
        </span>
    </div>
    <div class="col-2 text-right">
        <button class="btn btn-xs btn-icon p-0" type="button" title="<?= 'Rimuovi dai segnalibri' ?>">
            <span class="it-close sr-only"><?= 'Rimuovi dai segnalibri' ?></span>
            <svg class="icon icon-danger">
                <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#close-circle-outline"></use>
            </svg>
        </button>
    </div>
</div>