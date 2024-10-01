<?php

/**
 * Aria S.p.A.
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
 * @var string $message
 */

$this->title = AmosEvents::txt('#remove_signup_to_event_thankyou_title');

?>

<h3><?= $message; ?></h3>
<div class="btnViewContainer pull-left">
    <?= CloseButtonWidget::widget([
        'title' => AmosEvents::t('amosevents', '#go_back_to_event'),
        'layoutClass' => 'pull-left',
        'urlClose' => $event->getFullViewUrl()
    ]); ?>
</div>
