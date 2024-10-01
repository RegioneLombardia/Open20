<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\events\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\search\EventSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsCalendarEvents
 * @package open20\amos\events\widgets\graphics
 */
class WidgetGraphicsCalendarEvents extends WidgetGraphic
{
    /**
     * @var int Numero eventi visualizzati
     */
    const NUMBER_EVENTS = 5;

    /**
     * @var string ORDER_EVENTS ordimento event Carousel
     */
    const ORDER_EVENTS = 'begin_date_hour DESC';

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();

        $this->setLabel(AmosEvents::tHtml('amosevents', 'Events'));
        $this->setDescription(AmosEvents::t('amosevents', 'Elenca gli ultimi Eventi'));
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {

        /** @var EventSearch $search */
        $search = AmosEvents::instance()->createModel('EventSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());
        $events = $listEvents = $search->ultimeEvents($_GET, self::NUMBER_EVENTS);
        $eventsForCarousel = $search->ultimeEventsQuery($_GET, self::NUMBER_EVENTS)->orderBy(self::ORDER_EVENTS)->limit(self::NUMBER_EVENTS)->all();

        $viewToRender = 'agid_calendary';

        $moduleLayout = \Yii::$app->getModule('layout');

        if (is_null($moduleLayout)) {
            $viewToRender .= '_old';
        }


        return $this->render($viewToRender, [
            'listEvents' => $listEvents,
            'events' => $events,
            'eventsForCarousel' => $eventsForCarousel,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicLatestEvents',
            'numEvents' => self::NUMBER_EVENTS,
            'orderEvents' => self::ORDER_EVENTS
        ]);
    }
}
