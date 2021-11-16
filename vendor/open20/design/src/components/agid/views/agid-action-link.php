<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

?>


<div class="agid-action-link dropdown d-inline">
  <button class="btn btn-dropdown dropdown-toggle" type="button" id="viewActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <svg class="icon">
      <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-more-items"></use>
    </svg>
    <small>Vedi azioni</small>
  </button>
  <div class="dropdown-menu shadow-lg" aria-labelledby="viewActions" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 32px, 0px);">
    <div class="link-list-wrapper">
      <ul class="link-list">
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-download"></use>
            </svg>
            <span>Scarica</span>
          </a>
        </li>
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-print"></use>
            </svg>
            <span>Stampa</span>
          </a>
        </li>
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-hearing"></use>
            </svg>
            <span>Ascolta</span>
          </a>
        </li>
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-mail"></use>
            </svg>
            <span>Invia</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>