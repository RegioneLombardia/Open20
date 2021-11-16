<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\email
 * @category   CategoryName
 */

/**
 * @var \open20\amos\events\models\Event $event
 * @var \open20\amos\core\user\User $user
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var string $urlYes
 * @var string $urlNo
 */
?>

<h2><?= $event->title ?></h2>
<p><?= $event->summary ?></p>

<p>Gentile <?= $profile->getNomeCognome() ?>, sei stato invitato a questo evento, desideri partecipare?</p>

<a href="<?= $urlYes ?>">Si, mi iscrivo</a>
