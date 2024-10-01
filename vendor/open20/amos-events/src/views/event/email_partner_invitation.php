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
 * @var \open20\amos\core\user\User $user Main user
 * @var \open20\amos\admin\models\UserProfile $profile Main user profile
 * @var array $partner partner data
 * @var string $urlYes
 * @var string $urlNo
 */

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

?>

<h2><?= $event->title ?></h2>
<p><?= $event->summary ?></p>

<p><?= AmosEvents::t('amosevents', '#email_invitation_partner_text', [
        'nomeCognomeAccompagnatore' => $partner['name'], ' ', $partner['surname'],
        'nomeCognomePartecipante' => $profile->getNomeCognome()
    ]); ?></p>

<a href="<?= $urlYes ?>"><?= AmosEvents::t('amosevents', '#email_invitation_partner_yes'); ?></a>
<?php if ($eventsModule->saveExternalInvitations): ?>
    <a href="<?= $urlNo ?>"><?= AmosEvents::t('amosevents', '#email_invitation_partner_no'); ?></a>
<?php endif; ?>
