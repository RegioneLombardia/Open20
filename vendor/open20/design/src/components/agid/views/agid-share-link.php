<?php

use open20\design\assets\AgidDesignAsset;

$currentAsset = AgidDesignAsset::register($this);

?>


<div class="agid-share-link dropdown d-inline">
  <button class="btn btn-dropdown dropdown-toggle" type="button" id="shareActions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <svg class="icon">
    <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-share"></use>
    </svg>
    <small>Condividi</small>
  </button>
  <div class="dropdown-menu shadow-lg" aria-labelledby="shareActions" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 32px, 0px);">
    <div class="link-list-wrapper">
      <ul class="link-list">
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-facebook"></use>
            </svg>
            <span>Facebook</span>
          </a>
        </li>
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-twitter"></use>
            </svg>
            <span>Twitter</span>
          </a>
        </li>
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-linkedin"></use>
            </svg>
            <span>Linkedin</span>
          </a>
        </li>
        <li>
          <a class="list-item" href="#">
            <svg class="icon">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-whatsapp"></use>
            </svg>
            <span>Whatsapp</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>