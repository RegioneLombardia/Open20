<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

namespace open20\amos\events\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\events\AmosEvents;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconEventRooms
 * @package open20\amos\events\widgets\icons
 */
class WidgetIconEventRooms extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEvents::t('amosevents', 'Event Rooms'));
        $this->setDescription(AmosEvents::t('amosevents', 'Event Rooms'));
        $this->setIcon('calendar');
        $this->setUrl(['/events/event-room/index']);
        $this->setCode('EVENT_ROOM');
        $this->setModuleName('events');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge(
            $this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }
}
