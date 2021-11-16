<?php

use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$direction = (isset($itemDirection)) ? 'flex-xl-' . $itemDirection : '';
$categorySize  = (isset($categorySize)) ? $categorySize  : 'h6';
$titleSize = (isset($titleSize)) ? $titleSize : 'h5';
$descriptionSize = (isset($descriptionSize)) ? $descriptionSize : '';
$showSignature = (isset($showSignature)) ? $showSignature : 'false';
$showAvatar = (isset($showAvatar)) ? $showAvatar : 'true';
$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'md';

//https://www.php.net/manual/en/datetime.format.php
$day = DateUtility::getDate($date, 'php:d');
$month = DateUtility::getDate($date, 'php:M');
$year = DateUtility::getDate($date, 'php:Y');
$date = DateUtility::getDate($date);



$labelReadMore = (isset($labelReadMore)) ? $labelReadMore : 'Leggi';
$titleReadMore = (isset($titleReadMore)) ? $titleReadMore : 'Leggi la notizia' . ' ' . $title;

?>

<div class="news-item-list-wrapper">
  <div class="card-wrapper">
    <div class="card">
      <div class="card-body border-bottom border-light px-0">
        <div class="row">
          <div class="col-md-4">
          <img src="<?= $image ?>" alt="immagine evento" class="community-image img-fluid w-100 mh-100 mb-2" />
          </div>
          <div class="col-md-8">
            <div class="d-flex align-items-start">
            <div class="category-top mb-1">
            <span class="card-category text-secondary text-uppercase small font-weight-normal mb-0 <?= $customCategoryClass ?> text-uppercase rounded-0" <?php if ((!empty($colorBgCategory))) : ?> style="background-color: <?= $colorBgCategory ?> !important; padding: 0 4px; " <?php endif; ?>><strong <?php if ((!empty($colorTextCategory))) : ?> style="color: <?= $colorTextCategory ?> " <?php endif; ?>><?= $category ?></strong></span>
              
              <span class="data"><?= $date ?></span>
            </div>
            <div class="d-flex align-items-center ml-auto pl-3">
                <?php if (($newPubblication)) : ?>
                  <?php
                  echo $this->render(
                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                  );
                  ?>
                <?php endif; ?>
                <div class="ml-2">
                  <?php
                  echo $this->render(
                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget'
                  );
                  ?>
                </div>
              </div>
              </div>
            <div>
              <a href="<?= $url ?>" title="Vai alla news <?= $title ?>" class="link-list-title title-two-line">
                <h3 class="card-title font-weight-bold big-heading mb-2  <?= $titleSize ?>"><?= $title ?></h3>
              </a>

            </div>

            <p class="card-description small font-weight-light mb-2 title-two-line"><?= $description ?>

            </p>
            <div class="d-flex flex-wrap">
              <?php if (($showSignature)) : ?>
                <span class="card-signature mb-0 mr-3"><?= 'di' . ' ' . $nameSurname ?> </span>
              <?php endif; ?>
              <a class="read-more ml-auto" href="<?= $url ?>" title="<?= $titleReadMore ?>">
                <span class="text"><?= $labelReadMore ?></span>
                
              </a>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>
</div>