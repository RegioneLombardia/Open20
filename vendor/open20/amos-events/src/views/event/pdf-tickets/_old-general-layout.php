<?php
/**
 * @var \open20\amos\events\models\Event $eventData
 * @var object $qrcode immagine del qrcode
 * @var array $participantData
 */
?>

<h3><?= $eventData->title ?></h3>

    <p>Ora evento: <?= $eventData->begin_date_hour ?></p>

<br /><br />

<?php
$invitation = $participantData['companion_of'];
if($invitation->is_group){ ?>
    <b>Gruppo: </b> <?= $participantData['nome'] . ' ' .$participantData['cognome'] ?><br />
<?php } else { ?>
    <b>Nome: </b> <?= $participantData['nome'] ?><br />
    <b>Cognome: </b> <?= $participantData['cognome'] ?><br />
    <b>Azienda: </b> <?= $participantData['azienda'] ?><br />
<?php  } ?>
<?php if(!empty($participantData['codice_fiscale'])) : ?> <b>Codice Fiscale</b> <?= $participantData['codice_fiscale'] ?><br /><?php endif; ?>
<b>Note: </b> <?= $participantData['note'] ?><br />

<br /><br />

<?php if($eventData->seats_management) {?>
    <b><?=$participantData['seat']?></b>
<?php }?>

<?php if(!empty($participantData['accreditationModel'])) : ?>
    <b>Lista accreditamento</b> <?= $participantData['accreditationModel']->title; ?>
<?php endif; ?>

<br /><br />

<?php if(!empty($qrcode)) : ?>
Codice <?= $qrcode; ?>
<?php endif; ?>
