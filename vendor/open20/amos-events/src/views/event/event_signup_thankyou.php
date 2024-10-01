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
use open20\amos\events\helpers\google_api\widgets\AddEventTicketWidget;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\Event $event
 */

$this->title = AmosEvents::txt('#event_signup_thankyou_success');
$this->params['breadcrumbs'][] = $this->title;
$assetBundle = \open20\amos\events\assets\EventsAsset::register($this);

?>

<h3><?= AmosEvents::txt('Grazie di esserti registrato.'); ?></h3>
<h3><?= AmosEvents::txt('Abbiamo inviato una email di conferma e riepilogo.'); ?></h3>
<h3><?= AmosEvents::txt('Arrivederci a') . ' ' . $event->title; ?></h3>
<div class="col-xs-12 nop">
    <!--
</div>
<div class="btnViewContainer pull-left">
    <?= CloseButtonWidget::widget([
        'title' => AmosEvents::t('amosevents', '#go_back_to_event'),
        'layoutClass' => 'pull-left',
        'urlClose' => $event->getFullViewUrl()
    ]); ?>
</div>
