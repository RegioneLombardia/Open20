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

<h2><?= $event->title ?></h2>
<p><?= $event->summary ?></p>

<p>Ciao <?= $user['name'], ' ', $user['surname'] ?>, sei stato invitato a questo evento.</p>

Partecipi? <a href="<?= $url_yes ?>">Sì</a>, <a href="<?= $url_no ?>">no</a>


