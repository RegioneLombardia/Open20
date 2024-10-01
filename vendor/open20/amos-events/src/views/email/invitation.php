<?php

/**
 * Aria S.p.A.
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

<br><br>

<?php if(isset(\Yii::$app->params['googleApi'])): ?>
<a href="<?= \yii\helpers\Url::to(['/events/wallet/save-to-google', 'id' => $event->id], 'https'); ?>">
    <?= AmosEvents::t('amosevents', 'Save To Google Pay'); ?>
</a>
<?php endif; ?>
<?php if(isset(\Yii::$app->params['appleApi'])): ?>
<a href="<?= \yii\helpers\Url::to(['/events/wallet/save-to-ios', 'id' => $event->id], 'https'); ?>">
    <?= AmosEvents::t('amosevents', 'Save To Apple Wallet'); ?>
</a>
<?php endif; ?>