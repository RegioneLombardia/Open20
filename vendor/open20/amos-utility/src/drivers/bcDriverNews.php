<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers;

use open20\amos\utility\drivers\base\bcDriver;
use open20\amos\news\models\News;
use open20\amos\news\models\search\NewsSearch;
use open20\amos\news\widgets\icons\WidgetIconAdminAllNews;
use open20\amos\news\widgets\icons\WidgetIconNews;
use open20\amos\news\widgets\icons\WidgetIconAllNews;
use open20\amos\news\widgets\icons\WidgetIconNewsCreatedBy;
use open20\amos\news\widgets\icons\WidgetIconNewsDaValidare;

/**
 * 
 */
class bcDriverNews extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = News::className();
        $this->widgetIconNames = [
//            WidgetIconAdminAllNews::getIconName() => WidgetIconAdminAllNews::classname(),
            WidgetIconNews::getWidgetIconName() => WidgetIconNews::classname(),
            WidgetIconAllNews::getWidgetIconName() => WidgetIconAllNews::classname(),
//            WidgetIconNewsCreatedBy::getWidgetIconName() => WidgetIconNewsCreatedBy::classname(),
//            WidgetIconNewsDaValidare::getWidgetIconName() => WidgetIconNewsDaValidare::classname(),
        ];
    }

    public function searchWidgetIconAllNews()
    {
        $this->query = $this->cwhActiveQuery->getQueryCwhAll();
    }

    /**
     * 
     */
    public function searchWidgetIconNews()
    {

        $this->query = $this->cwhActiveQuery->getQueryCwhOwnInterest();
//        pr($this->query->createCommand()->rawSql);
    }
}