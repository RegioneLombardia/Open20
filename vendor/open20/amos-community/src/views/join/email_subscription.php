<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\join
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;

/**
 * @var \open20\amos\events\models\Event $event
 * @var \open20\amos\core\user\User $user
 * @var \open20\amos\admin\models\UserProfile $profile
 */

$message = AmosCommunity::t('amosevents', 'Gentile {name_surname}', ['name_surname' => $profile->getNomeCognome()]) . ', <br>' . AmosCommunity::t('amosevents', 'sei iscritto all\'evento di Blu Sea Land 2020:');
$messageAfter = AmosCommunity::t('amosevents', 'Riceverai notifiche riguradanti questo evento') .'<br>'. AmosCommunity::t('amosevents', 'Grazie e a presto.');
?>

<p><?= $message ?></p>
<p><b><?= $event->title ?></b><br><?= $event->summary ?></p>
<p><?= $messageAfter ?></p>