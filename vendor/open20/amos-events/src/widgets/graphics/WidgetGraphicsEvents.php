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
use open20\amos\events\models\EventConfigurations;
use open20\amos\events\models\search\EventSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsEvents
 * @package open20\amos\events\widgets\graphics
 */
class WidgetGraphicsEvents extends WidgetGraphic
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
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getHtml()
    {
        /** @var EventSearch $search */
        $search = AmosEvents::instance()->createModel('EventSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());
        $configurations = EventConfigurations::getConfigurations();

        $listEvents = $search->ultimeEvents($_GET, self::NUMBER_EVENTS);
        $eventsCarouselQuery = $search->ultimeEventsQuery($_GET, self::NUMBER_EVENTS)->orderBy(self::ORDER_EVENTS)->limit(self::NUMBER_EVENTS);
        if($configurations && !empty($configurations->from_days_to_now_visibility) ){
//            pr($configurations->from_days_to_now_visibility);die;
            $now = new \DateTime();
            $interval = new \DateInterval("P".$configurations->from_days_to_now_visibility."D");
            $startDateEnabled = $now->sub($interval);
            $listEvents->query->andWhere(['>', 'begin_date_hour' , $startDateEnabled->format('Y-m-d H:i:s')]);
            $eventsCarouselQuery->andWhere(['>', 'begin_date_hour' , $startDateEnabled->format('Y-m-d H:i:s')]);
        }

        $eventsForCarousel = $eventsCarouselQuery->all();
        $viewToRender = 'ultime_events';
        $moduleLayout = \Yii::$app->getModule('layout');

        if (is_null($moduleLayout)) {
            $viewToRender .= '_old';
        }

        if (count($eventsForCarousel) > 0) {
            return $this->render($viewToRender, [
                'listEvents' => $listEvents,
                'eventsForCarousel' => $eventsForCarousel,
                'widget' => $this,
                'toRefreshSectionId' => 'widgetGraphicLatestEvents',
                'numEvents' => self::NUMBER_EVENTS,
                'orderEvents' => self::ORDER_EVENTS
            ]);
        }
        return '';
    }
}
