<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

$columnWidth = (isset($columnWidth)) ? $columnWidth : 'col-12';
$topicTitleIcon = (isset($topicTitleIcon)) ? $topicTitleIcon : 'pin';

?>

<div class="<?= $additionalContainerClass ?> agid-evidence-topic-container">
    <div class="<?= $columnWidth ?> box-card p-5 shadow h-100">            
        <div class="uk-container">
            <div class="categoria-box-card">
                <div class="uk-container">
                    <div>
                        <svg class="icon icon-padded rounded-circle icon-white bg-primary d-flex  mr-1 icon-xl" role="img" aria-label="Numero risposte">
                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $topicTitleIcon ?>"></use>
                        </svg>
                    </div>
                </div>
            </div>
            <a href="#" class="text-black">
                <h5 class="title-box-card  pt-3 mb-0"> <?= $topicTitle ?></h5>
            </a>
            <div class="text-serif pt-3 uk-margin">
                <p>Tutte le notizie, informazioni e provvedimenti riguardanti il coronavirus (Covid-19)</p>
            </div>
            <ul class="link-list list-unstyled pl-0">
                <li class="my-2">
                    <a class="list-item active icon-left" href="#">
                        <svg class="icon icon-primary">
                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#calendar"></use>
                        </svg>
                        <span>Eventi questo weekend</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="cta-box-card">
            <a class="el-content d-flex align-items-center" href="/it/tutti-gli-argomenti" title="Esplora argomento">
                Esplora argomento  
                <svg class="icon icon-primary d-flex  mr-1 icon-xs" role="img" aria-label="Numero risposte">
                    <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#arrow-right"></use>
                </svg>               
            </a>
        </div>
    </div>
</div>
