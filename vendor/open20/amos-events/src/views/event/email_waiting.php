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
 * @var \open20\amos\admin\models\UserProfile $profile
 */

$message = AmosEvents::t('amosevents', 'Gentile {name_surname}', ['name_surname' => $profile->getNomeCognome()]) . ', ' .
    AmosEvents::t('amosevents', 'il numero massimo di posti disponibili è stato superato') . '.';

?>

<h2><?= $event->title ?></h2>
<p><?= $event->summary ?></p>

<p><?= $message ?></p>
