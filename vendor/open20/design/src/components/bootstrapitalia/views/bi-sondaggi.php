<?php

use open20\design\Module;
use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$direction = (isset($itemDirection)) ? 'flex-xl-' . $itemDirection : '';
$titleSize = (isset($titleSize)) ? $titleSize : 'h5';
$showSignature     = (isset($showSignature)) ? $showSignature : false; //firma alla fine della card
$model     = (isset($model) ? $model : null);
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);

//https://www.php.net/manual/en/datetime.format.php
$dayStart   = DateUtility::getDate($dateStart, 'php:d');
$monthStart = DateUtility::getDate($dateStart, 'php:M');
$yearStart  = DateUtility::getDate($dateStart, 'php:Y');
$dateStart  = DateUtility::getDate($dateStart);

$hideDateEnd = (isset($hideDateEnd) ? $hideDateEnd : false);
if (isset($dateEnd)) {
    $dayEnd   = DateUtility::getDate($dateEnd, 'php:d');
    $monthEnd = DateUtility::getDate($dateEnd, 'php:M');
    $yearEnd  = DateUtility::getDate($dateEnd, 'php:Y');
    $dateEnd  = DateUtility::getDate($dateEnd);
}


// if (isset($pollState)) {
//     if ($pollState == 'pubblicato') {
//         $stateLabel = Module::t('amosdesign', 'Nuovo');
//         $stateClass = "state-new";
//         $callToAction = Module::t('amosdesign', 'Apri');
//         $titleReadMore = Module::t('amosdesign', 'Apri il sondaggio');
//     } else if ($pollState == '2') {
//         $stateLabel = Module::t('amosdesign', 'Concluso');
//         $stateClass = "state-completed";
//         $callToAction = Module::t('amosdesign', 'Visualizza');
//         $titleReadMore = Module::t('amosdesign', 'Visualizza il sondaggio');
//     } else {
//         $stateLabel = Module::t('amosdesign', 'Da completare');
//         $stateClass = "state-to-complete";
//         $callToAction = Module::t('amosdesign', 'Completa');
//         $titleReadMore = Module::t('amosdesign', 'Completa il sondaggio');
//     }
// };

$stateLabel = $pollState;
$stateClass = "state" . '-' . str_replace(' ','-',strtolower($pollState));
$callToAction = Module::t('amosdesign', 'Visualizza');
$titleReadMore = Module::t('amosdesign', 'Visualizza il sondaggio');

$participants = (isset($participants)) ? $participants : '-';
$widthColumn = (isset($widthColumn)) ? $widthColumn :  'col-12';

?>

<div class="sondaggi-item-list-wrapper <?= $widthColumn ?>">
  <div class="card-wrapper">
    <div class="card">
      <div class="card-body border-bottom border-light px-0">
        <div class="row">
          <div class="date-list mb-1">
            <div class="date-start d-flex flex-sm-row flex-md-row flex-lg-column text-center text-uppercase h-100 justify-content-center">
                <span class="font-weight-light text-600 small"> <?=Module::t('amosdesign','Aperto il').' '?></span>
                <span class="card-day font-weight-bold text-600 lead"><?= $dayStart ?></span>
                <span class="card-month font-weight-bold text-600"><?= $monthStart ?></span>
                <span class="card-year font-weight-light text-600 small"><?= $yearStart ?></span>
            </div>
          </div>
          <?php if($image): ?>
            <div class="image-list mx-2 mb-1 object-fit">
              <img src="<?= $image ?>" alt="<?= Module::t('amosdesign', '#survey_image') ?>" class="community-image img-fluid w-100 mh-100" />
            </div>
          <?php endif; ?>

          <div class="description-list col mb-1 d-flex align-items-start flex-column">
            <div class="d-flex align-items-start bd-highlight w-100">

            <?php if (!$hideDateEnd) : ?>
                <div class="category-top mb-1">
                    <span class="font-weight-light"> <?= Module::t('amosdesign', 'Fino al') . ' ' ?></span>
                    <span class="card-day"><?= $dayEnd ?></span>
                    <span class="card-month"><?= $monthEnd ?></span>
                    <span class="card-year"><?= $yearEnd ?></span>
                </div>
              <?php endif ?>

              <div class="d-flex align-items-center ml-auto pl-3">
              <?php if (($pollState == '1')) : ?>
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

            <div class="list-title bd-highlight  mb-2">
              <a href="<?= $url ?>" title="Vai al sondaggio <?= $title ?>" class="link-list-title title-two-line">
                <h3 class="card-title mb-0 font-weight-bold big-heading <?= $titleSize ?>"><?= $title ?></h3>
              </a>
            </div>

            <p class="card-description small font-weight-light mb-2 title-two-line"><?= $description ?></p>

            <div class="footer-list d-flex mt-auto bd-highlight w-100">  
              <div class="pool-status mt-2 d-flex justify-content-between flex-column flex-sm-row text-uppercase w-100">
                  <div class="partecipanti-stato font-weight-bold">
                      <span class="font-weight-light"><?=Module::t('amosdesign','Partecipanti').':' . ' '?></span>
                      <span class="pertecipanti"><?= $participants ?></span>
                  </div>
                  <div class="partecipanti-stato font-weight-bold">
                      <span class="font-weight-light"><?=Module::t('amosdesign','Stato').':' . ' '?></span>
                      <span class="stato <?= $stateClass ?>"><?= $stateLabel ?></span>
                  </div>
                  <a class="read-more" href="<?= $url ?>" title="<?= $titleReadMore ?>">
                      <span class="text"><?= $callToAction ?></span>
                  </a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>


