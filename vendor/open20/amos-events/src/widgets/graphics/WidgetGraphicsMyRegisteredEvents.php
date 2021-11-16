<?php

/**
 * Lombardia Informatica S.p.A.
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
 * Class WidgetGraphicsEvents
 * @package open20\amos\events\widgets\graphics
 */
class WidgetGraphicsMyRegisteredEvents extends WidgetGraphic
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

        $this->setLabel(AmosEvents::tHtml('amosevents', 'Eventi a cui sono iscritto'));
        $this->setDescription(AmosEvents::t('amosevents', 'Elenca gli event ia cui sei iscritto'));
    }

    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        /** @var EventSearch $search */
        $search = AmosEvents::instance()->createModel('EventSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());
        $listEvents = $search->searchMyRegistrations($_GET, self::NUMBER_EVENTS);
        $eventsForCarousel = $search->searchMyRegistrationQuery($_GET)->orderBy(self::ORDER_EVENTS)->limit(self::NUMBER_EVENTS)->all();
//        pr($search->searchMyRegistrationQuery($_GET)->orderBy(self::ORDER_EVENTS)->limit(self::NUMBER_EVENTS)->createCommand()->rawSql);
        $viewToRender = 'registered_events';
        $moduleLayout = \Yii::$app->getModule('layout');

        if (is_null($moduleLayout)) {
            $viewToRender .= '_old';
        }

        return $this->render($viewToRender, [
            'listEvents' => $listEvents,
            'eventsForCarousel' => $eventsForCarousel,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicLatestEvents',
            'numEvents' => self::NUMBER_EVENTS,
            'orderEvents' => self::ORDER_EVENTS
        ]);
    }
}
