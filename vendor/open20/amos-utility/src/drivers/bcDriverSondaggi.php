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
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi;
use open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiOwnInterest;
use open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggiAll;

/**
 * 
 */
class bcDriverSondaggi extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = Sondaggi::classname(); // put here your model
        $this->widgetIconNames = [
//            WidgetIconSondaggi::getWidgetIconName() => WidgetIconSondaggi::classname(),
            WidgetIconCompilaSondaggiOwnInterest::getWidgetIconName() => WidgetIconCompilaSondaggiOwnInterest::classname(),
            WidgetIconCompilaSondaggiAll::getWidgetIconName() => WidgetIconCompilaSondaggiAll::classname(),
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconCompilaSondaggiOwnInterest()
    {
        $search      = new SondaggiSearch();
        $this->query = $search->searchOwnInterest([])->query;
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconCompilaSondaggiAll()
    {
        $search      = new SondaggiSearch();
        $this->query = $search->searchAll([])->query;
    }
}