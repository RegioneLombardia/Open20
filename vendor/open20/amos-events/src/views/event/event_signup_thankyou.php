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

$this->title = AmosEvents::txt('#event_signup_thankyou_success');
$this->params['breadcrumbs'][] = $this->title;
$module = \Yii::$app->getModule('events');
?>

<h3><?= AmosEvents::txt('Grazie di esserti registrato.'); ?></h3>
<h3><?= AmosEvents::txt('Abbiamo inviato una email di conferma e riepilogo.'); ?></h3>
<h3><?= AmosEvents::txt('Arrivederci a') . ' ' . $event->title; ?></h3>

<?php if ($module->enableNewWizard) {
    $urlClose = '/dashboard';
    $title = AmosEvents::t('amosevents', 'Close');
} else {
    $urlClose = $event->getFullViewUrl();
    $title = AmosEvents::t('amosevents', '#go_back_to_event');

}?>
    <div class="btnViewContainer pull-left">
        <?= CloseButtonWidget::widget([
            'title' => AmosEvents::t('amosevents', '#go_back_to_event'),
            'layoutClass' => 'pull-left',
            'urlClose' => $urlClose
        ]); ?>
    </div>

