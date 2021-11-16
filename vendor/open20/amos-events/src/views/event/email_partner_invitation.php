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
 * @var \open20\amos\core\user\User $user Main user
 * @var \open20\amos\admin\models\UserProfile $profile Main user profile
 * @var array $partner partner data
 * @var string $urlYes
 * @var string $urlNo
 */
?>

<h2><?= $event->title ?></h2>
<p><?= $event->summary ?></p>

<p>Gentile <?= $partner['name'], ' ', $partner['surname'] ?>, sei stato invitato come accompagnatore di <?= $profile->getNomeCognome() ?>, per partecipare a questo evento, desideri partecipare?</p>

<a href="<?= $urlYes ?>">Si, parteciperò</a>


