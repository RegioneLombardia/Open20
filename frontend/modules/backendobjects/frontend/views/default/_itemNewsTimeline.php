<?php

use open20\amos\news\utility\NewsUtility;
use open20\amos\core\utilities\CurrentUser;
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



$labelReadMore = (isset($labelReadMore)) ? $labelReadMore : 'Leggi tutto';
$titleReadMore = (isset($titleReadMore)) ? $titleReadMore : 'Leggi la notizia' . ' ' . $title;


$image = (!is_null($model->newsImage)) ? $model->newsImage->getWebUrl('square_medium', false, true) : '/img/img_default.jpg';

$hideCategory = false;
$newsCategories = NewsUtility::getNewsCategories();
if (count($newsCategories) == 1) {
  $hideCategory = true;
} else {
  $category = $model->newsCategorie->titolo;
}
?>

<div class="news-timeline-element my-5">
  <div class="it-pin-wrapper it-evidence">
    <div class="pin-icon">
      <svg class="icon">
        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-note"></use>
      </svg>
    </div>
  </div>
  <div class="content-news">
    <span class="date-news-timeline text-muted"><?= $model->sottotitolo ?></span>
    <h3 class="h4 text-black"><?= $model->getTitle(); ?></h3>
    <div class="text-news-timeline">
    <?= $model->descrizione ?>
    </div>
    </div>
</div>
