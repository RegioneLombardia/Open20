<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicAssistance $widget
 * @var Ticket[] $ticketsList
 * @var string $listTitle
 * @var array $linkToTicketList
 * @var string $listContainerClass
 */

?>

<div class="<?= $listContainerClass; ?>">
    <div>
        <h4><?= $listTitle; ?></h4>
        <?= Html::a(AmosIcons::show('open-in-new',['class' => 'am-2']), $linkToTicketList) ?>
    </div>
    <section>
        <?php if (count($ticketsList) == 0): ?>
            <div class="list-items list-empty"><p><?= AmosTicket::t('amosticket', '#widget_assistance_list_no_tickets') ?></p></div>
        <?php endif; ?>
        <div class="list-items">
            <?php foreach ($ticketsList as $ticket): ?>
                <div class="widget-listbox-option" role="option">
                    <article class="wrap-item-box">
                        <?= $this->render('_ticket_list_element', [
                            'widget' => $widget,
                            'ticket' => $ticket,
                            'listContainerClass' => $listContainerClass,
                        ]) ?>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
