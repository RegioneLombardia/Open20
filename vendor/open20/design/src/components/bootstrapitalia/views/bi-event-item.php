<?php

use open20\design\utility\DateUtility;

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$urlCta = (isset($urlCta)) ? $urlCta : $url;
$titleLinkCta = (isset($titleLinkCta)) ?: 'Maggiori informazioni sull\'evento' . ' ' . $title;
$classCta = 'bg-primary';
$classType = strtolower($type);

$paidLabel = ($isPaid) ? 'a pagamento' : '';
$availableSeatsLabel = (isset($availableSeats)) ? '(disponibili' . ' ' . $availableSeats . ' ' . 'posti)' : '';
$infos = $type. ' ' . $paidLabel . ' ' . $availableSeatsLabel;

//https://www.php.net/manual/en/datetime.format.php
$dayStart = DateUtility::getDate($dateHourStart, 'php:d');
$monthStart = DateUtility::getDate($dateHourStart, 'php:M');
$yearStart = DateUtility::getDate($dateHourStart, 'php:Y');
$hourStart = DateUtility::getDateHour($dateHourStart, 'php:H:i');
$dateStart = DateUtility::getDate($dateHourStart);

$dayEnd = DateUtility::getDate($dateHourEnd, 'php:d');
$monthEnd = DateUtility::getDate($dateHourEnd, 'php:M');
$yearEnd = DateUtility::getDate($dateHourEnd, 'php:Y');
$hourEnd = DateUtility::getDateHour($dateHourEnd, 'php:H:i');
$dateEnd = DateUtility::getDate($dateHourEnd);

$model     = (isset($model) ? $model : null);
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);

?>


<div class="event-list-item-container col-12 py-3 border-bottom d-flex ">
    <!--data container-->
    <div class="card-calendar event-calendar text-center d-flex flex-column rounded-0 mr-3 justify-content-center border border-secondary bg-secondary p-2">
        <span class="card-day font-weight-bold  h5 mb-0"><?= $dayStart ?></span>
        <span class="card-month text-uppercase font-weight-bold  small"><?= $monthStart ?></span>
    </div>
    <!--fine data container-->
    <div class="event-content d-flex align-items-center justify-content-between flex-grow-1 w-100">
        
            <div class="d-flex w-100 align-items-start">

            <div>
            <p class="event-hour font-weight-bold mb-0 small">
                <?php if ((isset($dateHourEnd))) : ?>
                    <?php if ($dateStart != $dateEnd) : ?>
                        <?= 'dalle ore' . ' ' . $hourStart . ' ' . 'al' . ' ' . $dateEnd . ' ' . 'alle ore' . ' ' . $hourEnd ?>
                    <?php else : ?>
                        <?= 'dalle ore' . ' ' . $hourStart . ' ' . 'alle ore' . ' ' . $hourEnd ?>
                    <?php endif ?>
                <?php else : ?>
                    <?= 'alle ore' . ' ' . $hourStart ?>
                <?php endif ?>
            </p>
            <a class="event-title " href="<?= $url ?>" title="Maggiori informazioni sull\'evento <?= $title ?>">
                <h3 class="mb-0 h5 font-weight-bold"><?= $title ?></h3>
            </a>
            </div>
           <!-- <span class="event-info badge badge-< ?= $classType ?> mb-0">
                < ?= $type ?>
            </span> -->
            <div class="other-info-item d-flex align-items-center justify-content-end ml-auto">

                <?php if (($newPubblication)) : ?>
                    <div class="ml-2">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                        );
                        ?>
                    </div>
                <?php endif; ?>
                <div class="ml-2">
                    <?php
                        echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                        [
                            'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete),
                            'model' => $model
                        ]
                        );
                    ?>
                </div>
            </div>
            </div>
            <?php if (isset($numPlaces) && !empty($numPlaces)) : ?>
                <?php if (isset($numPlaces['available'])) : ?>
                    <p class="mb-0"><?= Module::t('amosdesign', 'Numero posti disponibili') . ' ' . $numPlaces['available'] ?> <?= isset($numPlaces['tot']) ? 'su ' . $numPlaces['tot']  : '' ?></p>
                <?php else : ?>
                    <p class="mb-0"><?= Module::t('amosdesign', 'Numero posti totali') . ' ' .  $numPlaces['tot'] ?></p>
                <?php endif; ?>
            <?php endif; ?>
        
        
    </div>
</div>