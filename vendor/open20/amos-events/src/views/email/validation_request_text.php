<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\email
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;

/**
 * @var \open20\amos\events\models\Event $event
 * @var \open20\amos\core\user\User $user
 */

?>

<h2>
    <?= AmosEvents::t('amosevents', 'Has been requested the validation for event'); ?>
</h2>
<?= AmosEvents::t('amosevents', 'Event type') . ': ' . !is_null($event->eventType) ? $event->eventType->title : '-'. '<br>'; ?>
<?= AmosEvents::t('amosevents', 'Event title') . ': ' . $event->title . '<br>'; ?>
<?= AmosEvents::t('amosevents', 'Event summary') . ': ' . $event->summary . '<br>'; ?>
<?= AmosEvents::t('amosevents', 'Published by') . ': ' . $user->userProfile->getNomeCognome(); ?>
