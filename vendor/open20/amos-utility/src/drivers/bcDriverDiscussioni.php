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
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAdminAll;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAll;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare;

/**
 * 
 */
class bcDriverDiscussioni extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = DiscussioniTopic::className(); // put here your model
        $this->module          = 'discussioni'; // raw fix db incongruence
        $this->widgetIconNames = [
//            WidgetIconDiscussioni::getWidgetIconName() => WidgetIconDiscussioni::classname(),
            WidgetIconDiscussioniTopic::getWidgetIconName() => WidgetIconDiscussioniTopic::classname(),
//            WidgetIconDiscussioniTopicAdminAll::getWidgetIconName() => WidgetIconDiscussioniTopicAdminAll::classname(),
            WidgetIconDiscussioniTopicAll::getWidgetIconName() => WidgetIconDiscussioniTopicAll::classname(),
//            WidgetIconDiscussioniTopicCreatedBy::getWidgetIconName() => WidgetIconDiscussioniTopicCreatedBy::classname(),
//            WidgetIconDiscussioniTopicDaValidare::getWidgetIconName() => WidgetIconDiscussioniTopicDaValidare::classname()
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconDiscussioniTopicAdminAll()
    {
        $search      = new DiscussioniTopicSearch();
        $this->query = $search->buildQuery('admin-all', []);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconDiscussioniTopicDaValidare()
    {
        $search      = new DiscussioniTopicSearch();
        $this->query = $search->buildQuery('to-validate', []);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconDiscussioniTopic()
    {

        $search      = new DiscussioniTopicSearch();
        $this->query = $search->buildQuery('own-interest', []);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconDiscussioniTopicAll()
    {

        $search      = new DiscussioniTopicSearch();
        $this->query = $search->buildQuery('all', []);
    }
}