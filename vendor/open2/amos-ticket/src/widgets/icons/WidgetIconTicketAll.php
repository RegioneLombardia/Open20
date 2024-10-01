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
 * Class WidgetIconTicketAll
 * @package open2\amos\ticket\widgets\icons
 */
class WidgetIconTicketAll extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosTicket::tHtml('amosticket', 'Tutti i ticket'));
        $this->setDescription(AmosTicket::t('amosticket', 'Visualizza tutti i ticket'));
        $this->setIcon('feed');
        $this->setUrl(['/ticket/ticket/index']);
        $this->setCode('TICKET_ALL');
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
