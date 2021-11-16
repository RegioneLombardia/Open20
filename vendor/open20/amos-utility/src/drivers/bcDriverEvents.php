<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers;

use open20\amos\utility\drivers\base\bcDriver;
use open20\amos\events\models\Event;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\widgets\icons\WidgetIconAllEvents;
use open20\amos\events\widgets\icons\WidgetIconEventOwnInterest;
use open20\amos\events\widgets\icons\WidgetIconEvents;
use open20\amos\events\widgets\icons\WidgetIconEventsCreatedBy;

/**
 * 
 */
class bcDriverEvents extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = Event::className(); // put here your model
        $this->widgetIconNames = [
            WidgetIconAllEvents::getWidgetIconName() => WidgetIconAllEvents::classname(),
            WidgetIconEventOwnInterest::getWidgetIconName() => WidgetIconEventOwnInterest::classname(),
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEventsCreatedBy()
    {
        $search       = new EventSearch();
        $dataProvider = $search->searchCreatedBy([]);
        $this->query  = $dataProvider->query;
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconAllEvents()
    {
        $search       = new EventSearch();
        $dataProvider = $search->searchAllEvents([]);
        $this->query  = $dataProvider->query;
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEventOwnInterest()
    {
        $search      = new EventSearch();
        $this->query = $search->buildQuery([], 'own-interest');
    }
}