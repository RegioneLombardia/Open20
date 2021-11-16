<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $subject
 * @var array $userData
 */

echo AmosChat::tHtml('amoschat','Hai ricevuto ') . $userData['msgCount'] . AmosChat::tHtml('amoschat',' messaggi/messaggio su ') . Yii::$app->name . AmosChat::tHtml('amoschat',' da ') . $userData['senderCompleteName'];
?>
<div>
    <?= Html::a(AmosChat::t('amoschat','Clicca qui'), ['/messages/' . $userData['sender_id']]) . AmosChat::t('amoschat',' per rispondere alla conversazione.') ?>
</div>