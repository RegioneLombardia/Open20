<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseButtonWidget;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\controllers\TicketController;

/**
 * @var yii\web\View $this
 * @var open2\amos\ticket\models\Ticket $model
 */
if ($model->ticketCategoria) {
    $this->title = AmosTicket::t('amosticket', 'Nuovo ticket');
}

/** @var TicketController $appController */
$appController = Yii::$app->controller;
?>

<div class="ticket-create m-t-10">
    <?= AmosTicket::t('amosticket', '#create_ticket_thankyou_message', ['categoryName' => $model->ticketCategoria->nomeCompleto]) ?>
</div>
<div class="btnViewContainer pull-left">
    <?=
    CloseButtonWidget::widget([
        'title' => AmosTicket::t('amosticket', '#go_back'),
        'layoutClass' => 'pull-left',
        'urlClose' => $appController->getViewCloseUrl()
    ])
    ?>
</div>