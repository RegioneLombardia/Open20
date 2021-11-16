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
 */

?>

<?= AmosEvents::t('amosevents', 'The event') . " '" . $event->title . "' " . AmosEvents::t('amosevents', 'has been published') . '.'; ?>
