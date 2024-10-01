<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\graphics\views
 * @category   CategoryName
 */

use open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance;
use yii\data\ActiveDataProvider;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicAssistance $widget
 * @var ActiveDataProvider $waitingTicketsList
 * @var ActiveDataProvider $inProgressTicketsList
 * @var ActiveDataProvider $closedTicketsList
 */

echo $this->render('fullsize/assistance_widget', [
    'widget' => $widget,
    'waitingTicketsList' => $waitingTicketsList,
    'inProgressTicketsList' => $inProgressTicketsList,
    'closedTicketsList' => $closedTicketsList,
]);
