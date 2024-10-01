<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\widgets\icons
 * @category   CategoryName
 */

namespace open2\amos\ticket\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open2\amos\ticket\AmosTicket;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconTicketAdminFaq
 * @package open2\amos\ticket\widgets\icons
 */
class WidgetIconTicketAdminFaq extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosTicket::tHtml('amosticket', 'Gestione FAQ'));
        $this->setDescription(AmosTicket::t('amosticket', 'Gestione delle FAQ'));
        $this->setIcon('feed');
        $this->setUrl(['/ticket/ticket-faq/index']);
        $this->setCode('TICKET_ADMIN_FAQ');
        $this->setModuleName('ticket');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-primary'
                ]
            )
        );
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }

}
