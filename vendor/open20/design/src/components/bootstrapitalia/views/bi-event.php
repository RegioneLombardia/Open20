<?php

use open20\design\utility\DateUtility;
use open20\design\Module;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$image = (isset($image)) ? $image : 'https://picsum.photos/1600/900?random=5';
$theme = (isset($theme)) ? $theme : 'light';

$labelCtaSubscribe = (isset($customLabelCtaSubscribe)) ? $customLabelCtaSubscribe : 'Iscriviti all\'evento';
$labelCtaView = (isset($customLabelCtaView)) ? $customLabelCtaView : 'Visualizza evento';

/**
 * commentata la seguente logica per allineare il comportamento con la view
 * '_icon' di Amos Events
 */
//if (strpos($urlCta, 'event-signup') !== false) {
//    $titleLinkCta = (isset($titleLinkCta)) ?: 'Iscriviti all\'evento' . ' ' . $title;
//    $labelCta = $labelCtaSubscribe;
//    $classCta = 'btn-primary';
//} else if (strpos($urlCta, 'view') !== false) {
//    $urlCta = (isset($urlCta)) ? $urlCta : $url;
//    $titleLinkCta = (isset($titleLinkCta)) ?: 'Maggiori informazioni sull\'evento' . ' ' . $title;
//    $labelCta = $labelCtaView;
//    $classCta = 'btn-link px-0';
//} else if (strpos($urlCta, 'open-join') !== false) {
//    $urlCta = (isset($urlCta)) ? $urlCta : $url;
//    $titleLinkCta = (isset($titleLinkCta)) ?: 'Maggiori informazioni sull\'evento' . ' ' . $title;
//    $labelCta = $labelCtaView;
//    $classCta = 'btn-link px-0';
//} else {
//    //caso prettyurl
//    $urlCta = (isset($urlCta)) ? $urlCta : $url;
//    $titleLinkCta = (isset($titleLinkCta)) ?: 'Maggiori informazioni sull\'evento' . ' ' . $title;
//    $labelCta = $labelCtaView;
//    $classCta = 'btn-link px-0';
//}
$urlCta = $url;
$titleLinkCta = 'Vai alla pagina dell\'evento ' . $title;
$labelCta = 'Visualizza evento';
$classCta = 'btn-link px-0';


$classType = strtolower($type);

$paidLabel = ($isPaid) ? 'a pagamento' : '';
$availableSeatsLabel = (isset($availableSeats)) ? '(disponibili' . ' ' . $availableSeats . ' ' . 'posti)' : '';
$infos = ($isPaid) ? $type . ', ' . $paidLabel . ' ' . $availableSeatsLabel : $type;

//https://www.php.net/manual/en/datetime.format.php
$dayStart = DateUtility::getDate($dateHourStart, 'php:d');
$monthStart = DateUtility::getDate($dateHourStart, 'php:M');
$yearStart = DateUtility::getDate($dateHourStart, 'php:Y');
$hourStart = DateUtility::getDateHour($dateHourStart, 'php:H:i');

$model = (isset($model) ? $model : null);
$actionModify = (isset($actionModify) ? $actionModify : null);
$actionDelete = (isset($actionDelete) ? $actionDelete : null);

$widthColumn = (isset($widthColumn)) ? $widthColumn : 'col-md-6';


?>

<div class="event-container d-flex flex-column <?= $widthColumn ?> <?= $theme ?>-theme">
    <div class="d-flex flex-column-reverse flex-md-row h-100">
        <div class="time-event d-flex flex-row flex-md-column mb-3 mb-md-0 mr-md-3">
            <div class="date py-2 px-2 px-sm-3 d-flex flex-md-column justify-content-md-center align-items-center text-uppercase flex-md-grow-1">
                <p class="day-event pr-1 pr-md-0 font-weight-bold mb-0"><?= $dayStart ?></p>
                <p class="month-event font-weight-bold pr-1 pr-md-0 mb-0"><?= $monthStart ?></p>
                <p class="year-event font-weight-normal mb-0"><?= $yearStart ?></p>
            </div>
            <div class="hour d-flex align-items-center justify-content-start py-3 px-2 px-sm-3 ml-auto">
                <svg class="icon icon-sm d-flex smr-1" role="img" aria-label="Ora di inizio evento">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#clock-outline"></use>
                </svg>
                <p class="mb-0"><?= $hourStart ?></p>
            </div>

        </div>
        <div class="external-image-container w-100 h-100">
            <div class="image-wrapper">
                <a href="<?= $url ?>" title="Dettagli evento <?= $title ?>">
                    <img class="community-image img-fluid w-100" src="<?= $image ?>" alt="immagine evento"/>
                </a>
                <!--    < ?php if (!$inScope) : ?>
                    <span class="badge badge-< ?= $classType ?> mb-0 p-1">< ?= $type ?>
                </span>
                < ?php endif ?> -->
            </div>
            <div class="other-info-item d-flex align-items-center pl-3">
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

    </div>
    <?php if (!$inScope) : ?>
        <a href="<?= $url ?>" class="link-list-title mt-md-4 mb-0" title="Dettagli evento <?= $title ?>">
            <h3 class="h5 title-one-line font-weight-bold mb-0"><?= $title ?></h3>
        </a>
        <p class="event-summary mb-0"><?= $summary ?></p>
    <?php endif ?>
    <!--<div class="event-cta-cont">-->
    <?php if ($dateSigned) : ?>
        <div class="d-flex">
            <small class="d-flex align-items-end text-muted mt-2">
                <?= Module::t('amosdesign', 'Iscritto il') . ' ' ?><?= $dateSigned ?>
            </small>
        </div>
    <?php endif ?>
        <div class="d-flex flex-wrap">
            <?= \yii\helpers\Html::a($labelCta, $urlCta, [
                'class' => "btn $classCta my-3 align-self-start mx-0",
                'title' => $titleLinkCta
            ]) ?>
            <?php if (!empty($model->community_id) && ($model->show_community)): ?>
            <?php   $createUrlParams = [
                        '/community/join/open-join',
                        'id' => $model->community_id
                    ]; ?>
            <a class="btn btn-link my-3" href="<?= Yii::$app->urlManager->createUrl($createUrlParams) ?>"
               title="Vai alla community dell'evento <?= $model->title ?>">
                <p class="mb-0"><?= Module::t('amosdesign', "Community dell'evento") ?></p>
            </a>
            <?php endif; ?>
        </div>
    <!--</div>-->

</div>