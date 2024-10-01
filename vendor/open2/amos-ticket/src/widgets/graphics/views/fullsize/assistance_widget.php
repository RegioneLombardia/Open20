<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance;

\open2\amos\ticket\assets\TicketAsset::register($this);

/**
 * @var yii\web\View $this
 * @var WidgetGraphicAssistance $widget
 * @var Ticket[] $waitingTicketsList
 * @var Ticket[] $inProgressTicketsList
 * @var Ticket[] $closedTicketsList
 */

?>
<div class="box-widget-header">
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('assistenza', ['class' => 'am-2'], AmosIcons::IC) ?>
            <?= AmosTicket::t('amosticket', '#widget_graphic_assistance_label') ?>
        </h2>
    </div>
</div>
<div class="box-widget box-widget-column assistance-widget">
    <section>
        <div class="list-items">
            <?= $this->render('_ticket_list', [
                'widget' => $widget,
                'ticketsList' => $waitingTicketsList,
                'listTitle' => AmosTicket::t('amosticket', 'Ticket in attesa'),
                'linkToTicketList' => ['/ticket/ticket/ticket-waiting'],
                'listContainerClass' => 'widget-listbox-option waiting-tickets',
            ]) ?>
            <?= $this->render('_ticket_list', [
                'widget' => $widget,
                'ticketsList' => $inProgressTicketsList,
                'listTitle' => AmosTicket::t('amosticket', 'Ticket in lavorazione'),
                'linkToTicketList' => ['/ticket/ticket/ticket-processing'],
                'listContainerClass' => 'widget-listbox-option processing-tickets',
            ]) ?>
            <?= $this->render('_ticket_list', [
                'widget' => $widget,
                'ticketsList' => $closedTicketsList,
                'listTitle' => AmosTicket::t('amosticket', 'Ticket chiusi'),
                'linkToTicketList' => ['/ticket/ticket/ticket-closed'],
                'listContainerClass' => 'widget-listbox-option closed-tickets',
            ]) ?>
        </div>
    </section>
</div>
