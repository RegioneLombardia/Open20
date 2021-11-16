<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

$columnWidth = (isset($columnWidth)) ? $columnWidth : 'col-12';

?>


<div class="<?= $columnWidth ?> agid-item-search-result-container">
    <div class="card card-teaser flex-grow-1 d-flex">
      <div class="card-body d-flex flex-column flex-grow-1 h-100">
        <div class="category-top d-flex">
          <svg class="icon <?= $iconClass ?>">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?= $icon ?>"></use>
          </svg>
          <p class="category <?= $categoryClass?>" ><?= $category ?></p>
        </div>
        <p class="card-title h4">
          <a class="<?=$titleClass?>" href="<?=$cardLink?>"><?= $cardTitle?></a>
        </p>
        <p class="card-text">
          <?= $cardDescription ?>
        </p>
        <a class="mt-3 read-more position-relative flex-grow-1 d-flex align-items-end" style="bottom:auto" href="<?=$cardLink?>">Leggi tutto
          <svg class="icon <?= $iconClass ?>">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#arrow-right"></use>
          </svg></a>

      </div>
    </div>
</div>