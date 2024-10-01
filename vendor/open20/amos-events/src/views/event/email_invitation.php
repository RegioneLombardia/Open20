<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;

/**
 * @var \open20\amos\events\models\Event $event
 * @var \open20\amos\core\user\User $user
 * @var string $urlYes
 * @var string $urlNo
 */

$eventLocation = '-';
if ($event->event_location) {
    if ($event->hasMethod('getShortEventLocation')) {
        $eventLocation = $event->getShortEventLocation();
    } else {
        $eventLocation = $event->event_location;
    }
}

$eventRoom = $event->eventRoom;
$eventRoomName = '';
if (!is_null($eventRoom)) {
    $eventRoomName = ' - ' . $eventRoom->room_name;
}

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

?>

<h2><?= $event->title ?></h2>
<p><?= $event->summary ?></p>
<br>
<p><?= $eventLocation . $eventRoomName ?></p>
<p><?= $event->getCompleteAddressForView() ?></p>
<p><?= $event->getEventBeginEndForView() ?></p>
<br>

<p><?= AmosEvents::t('amosevents', '#email_invitation_text', ['nomeCognome' => $user['name'], ' ', $user['surname']]); ?></p>

<a href="<?= $urlYes ?>"><?= AmosEvents::t('amosevents', '#email_invitation_yes'); ?></a>
<?php if ($eventsModule->saveExternalInvitations): ?>
    <br><a href="<?= $urlNo ?>"><?= AmosEvents::t('amosevents', '#email_invitation_no'); ?></a>
<?php endif; ?>
