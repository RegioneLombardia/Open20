<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-wizard\layouts
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;

/**
 * @var string $publishingEntities
 * @var string $recipients
 */
?>

<dl>
    <dt><?= AmosEvents::tHtml('amosevents', 'Published by') ?></dt>
    <dd><?= $publishingEntities ?></dd>
</dl>
<dl>
    <dt><?= AmosEvents::tHtml('amosevents', 'Recipients') ?></dt>
    <dd><?= $recipients ?></dd>
</dl>
