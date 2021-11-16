<?php

use \open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
$totInvitedUsers = $model->getTotParticipantsPlusCompanions();
$totInvitedCompanions = $model->getEventParticipantCompanions()->count();
$totWaitingUsers = $model->getTotWaitingParticipants();
?>

<p>
    <svg class="icon icon-sm mr-1">
        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_event_seat"></use>
    </svg>
    <strong><?= AmosEvents::t('amosevents', "POSTI DISPONIBILI") ?></strong>
</p>
<?php if ($model->eventType->limited_seats) { ?>
    <?php if ($model->isSeatManagement()) { ?>
        <?= AmosEvents::t('amosevents', '{x} Utenti invitati', [
            'x' => $totInvitedUsers - $totInvitedCompanions,
        ]) ?>
        <br>
        <?= AmosEvents::t('amosevents', '{x} Utenti invitati da gruppi', [
            'x' => $totInvitedCompanions,
        ]) ?>
        <br>
        <strong><?= AmosEvents::t('amosevents', 'Totale utenti invitati {x}', [
                'x' => $totInvitedUsers,
            ]) ?>
        </strong>
        <br>
        <br>
        <?php
        $totSeats = $model->getEventSeats()->count();
        $totAssigned = $model->getEventSeats()
            ->andWhere(['status' => [\open20\amos\events\models\EventSeats::STATUS_ASSIGNED, \open20\amos\events\models\EventSeats::STATUS_REASSIGNED]])->count();
        ?>
        <?= AmosEvents::t('amosevents', '{x} su {y} posti disponibili assegnati', [
            'x' => $totAssigned,
            'y' => $totSeats
        ]) ?>
        <br>
        <?= AmosEvents::t('amosevents', 'POSTI ANCORA DISPONIBILI: ') ?><?= $totSeats - $totAssigned ?>

        <?php $percentage = round(($totAssigned / $totSeats) * 100); ?>

        <div class="progress">
            <div class="progress-bar bg-success" style="width:<?= $percentage ?>%"></div>
        </div>
    <?php } else { ?>
    <?= AmosEvents::t('amosevents', '{x} Utenti confermati', [
        'x' => $totParticipants,
    ]) ?>
    <br>
    <?= AmosEvents::t('amosevents', '{x} Utenti in lista d\'attesa', [
        'x' => $totWaitingUsers,
    ]) ?>
    <br>
    <strong><?= AmosEvents::t('amosevents', 'Totale utenti registrati {x}', [
            'x' => $totParticipants + $totWaitingUsers,
        ]) ?></strong>
        <br>
        <br>
        <?= AmosEvents::t('amosevents', '<strong>TOTALE: {x} partecipanti su {y} posti disponibili</strong>', [
            'x' => $totParticipants,
            'y' => $model->seats_available
        ]) ?>

        <?php
        if ($model->community_id) {
            $seatsAvailable = \open20\amos\events\utility\EventsUtility::cmpSeatsAvailable($model);
            ?>
            <?php $seatsRemaining = $seatsAvailable - $totParticipants;
            $seatsRemaining = ($seatsRemaining < 0) ? 0 : $seatsRemaining ?>
            <p style="color:#28a745"
               class="font-weight-bold mb-2"><?= AmosEvents::t('amosevents', 'POSTI ANCORA DISPONIBILI: ') ?><?= $seatsRemaining ?></p>
            <?php $percentage = round(($totParticipants / $model->seats_available) * 100); ?>
            <div class="progress-bar-wrapper">

                <div class="progress progress-color progress-h-10">
                    <div class="progress-bar bg-success" style="width:<?= $percentage ?>%"></div>
                </div>
                <div class="progress-bar-label"><span class="sr-only">Progresso </span><?= $percentage ?>%</div>
            </div>
        <?php } ?>
        <?php } ?>
    <?php } else { ?>
    <strong><?= AmosEvents::t('amosevents', 'Totale utenti registrati: {x}', [
            'x' => ''.$totParticipants,
        ]) ?></strong>
    <br>
    <?php } ?>
