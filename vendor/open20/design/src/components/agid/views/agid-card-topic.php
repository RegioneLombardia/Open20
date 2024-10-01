<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

$columnWidth = (isset($columnWidth)) ? $columnWidth : 'col-12';
$topicIcon = (isset($topicIcon)) ? $topicIcon : 'help-circle-outline';

?>


<div class="<?= $additionalContainerClass ?> agid-evidence-topic-container">
    <div class="<?= $columnWidth ?> card card-teaser rounded shadow align-items-center">
        <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $topicIcon ?>"></use>    
        </svg>
        <div class="card-body">
            <p class="card-title font-weight-bold lead">
                <a href="<?= $topicLink ?>"><?= $topicTitle ?></a>
            </p>
        </div>
    </div>
</div>