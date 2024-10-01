<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\events\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\events\AmosEvents;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSubscribedEvents
 * @package open20\amos\events\widgets\icons
 */
class WidgetIconSubscribedEvents extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosEvents::t('amosevents', '#widget_icon_subscribed_events_label'));
        $this->setDescription(AmosEvents::t('amosevents', '#widget_icon_subscribed_events_description'));
        $this->setIcon('calendar');
        $this->setUrl(['/events/event/subscribed-events']);
        $this->setCode('SUBSCRIBED_EVENTS');
        $this->setModuleName('events');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge(
            $this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }
}
