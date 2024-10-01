<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\graphics
 * @category   CategoryName
 */

namespace open2\amos\ticket\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\search\TicketSearch;

/**
 * Class WidgetGraphicAssistance
 * @package open2\amos\ticket\widgets\graphics
 */
class WidgetGraphicAssistance extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('ASSISTANCE_GRAPHIC');
        $this->setLabel(AmosTicket::t('amosticket', '#widget_graphic_assistance_label'));
        $this->setDescription(AmosTicket::t('amosticket', '#widget_graphic_assistance_description'));
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $viewToRender = '@vendor/open2/amos-ticket/src/widgets/graphics/views/assistance_widget';
        $numberToView = 3;

        /** @var TicketSearch $searchModel */
        $searchModel = AmosTicket::instance()->createModel('TicketSearch');
        $waitingTicketsList = $searchModel->searchTicketWaiting($_GET, $numberToView)->getModels();
        $inProgressTicketsList = $searchModel->searchTicketProcessing($_GET, $numberToView)->getModels();
        $closedTicketsList = $searchModel->searchTicketClosed($_GET, $numberToView)->getModels();

        return $this->render(
            $viewToRender,
            [
                'widget' => $this,
                'waitingTicketsList' => $waitingTicketsList,
                'inProgressTicketsList' => $inProgressTicketsList,
                'closedTicketsList' => $closedTicketsList,
            ]
        );
    }
}
