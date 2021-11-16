<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\events\AmosEvents;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\Event $event
 */

$this->title = AmosEvents::txt('#event_signup_thankyou_waiting');
$this->params['breadcrumbs'][] = $this->title;

?>

<h3><?= AmosEvents::txt('Grazie di esserti registrato.'); ?></h3>
<h3><?= AmosEvents::txt('#event_signup_thankyou_msg_1'); ?></h3>
<h3><?= AmosEvents::txt('#event_signup_thankyou_msg_2'); ?></h3>
<div class="btnViewContainer pull-left">
    <?= CloseButtonWidget::widget([
        'title' => AmosEvents::t('amosevents', '#go_back_to_event'),
        'layoutClass' => 'pull-left',
        'urlClose' => $event->getFullViewUrl()
    ]); ?>
</div>
