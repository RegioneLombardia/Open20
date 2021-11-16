<?php

/**
 * @var \open20\amos\admin\models\UserProfile $userProfile
 * @var array $companions
 * @var string $downloadTicketsLink
 * @var string $downloadIcsLink
 * @var \open20\amos\events\models\Event $event
 * @var \open20\amos\events\models\EventInvitation $invitation
 */

?>
<?php if (!empty($ticketText)) {
    echo $ticketText;
} else { ?>
    <p><?= \open20\amos\events\AmosEvents::txt('Gentile {name_surname}', ['name_surname' => $userProfile->getNomeCognome()]) ?>
        ,</p>
    <p><?= \open20\amos\events\AmosEvents::txt('Ringraziandoti per la partecipazione, ti consegniamo i biglietti di ingresso a {event_title} che si svolgerà il {event_date} per', ['event_title' => $event->title, 'event_date' => date("d-m-Y H:i:s", strtotime($event->begin_date_hour))]) ?></p>
<?php } ?>


<p><?= $userProfile->getNomeCognome(); ?></p>
<?php
foreach ($companions as $companion) {
    echo "<p>{$companion['nome']} {$companion['cognome']}</p>";
}
?>
<br/>
<p>
    <a href="<?= $downloadTicketsLink ?>"><?= \open20\amos\events\AmosEvents::txt('Click here to download your tickets'); ?></a>
</p>
<br/>
<p>
    <a href="<?= $downloadIcsLink ?>"><?= \open20\amos\events\AmosEvents::txt('Per scaricare il file calendario (.ics), clicca qui.'); ?></a>
</p>
<br/>
<p><?= $event->description; ?></p>
<br/>
<p><?= $event->getFullAddress("<br />") ?></p>
<br/>
<p><?= "TERMINI E CONDIZIONI" ?></p>
