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
use open20\amos\community\models\Community;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\community\widgets\icons\WidgetIconAdminAllCommunity;
use open20\amos\community\widgets\icons\WidgetIconCommunity;
use open20\amos\community\widgets\icons\WidgetIconCreatedByCommunities;
use open20\amos\community\widgets\icons\WidgetIconMyCommunities;
use open20\amos\community\widgets\icons\WidgetIconMyCommunitiesWithTags;
use open20\amos\community\widgets\icons\WidgetIconToValidateCommunities;

/**
 * 
 */
class bcDriverCommunity extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = Community::classname(); // put here your model
        $this->widgetIconNames = [
//            WidgetIconAdminAllCommunity::getWidgetIconName() => WidgetIconAdminAllCommunity::classname(),
            WidgetIconMyCommunities::getWidgetIconName() => WidgetIconMyCommunities::classname(),
            WidgetIconCommunity::getWidgetIconName() => WidgetIconCommunity::classname(),
//            WidgetIconCreatedByCommunities::getWidgetIconName() => WidgetIconCreatedByCommunities::classname(),
//            WidgetIconMyCommunitiesWithTags::getWidgetIconName() => WidgetIconMyCommunitiesWithTags::classname(),
//            WidgetIconToValidateCommunities::getWidgetIconName() => WidgetIconToValidateCommunities::classname()
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconAdminAllCommunity()
    {

        $search      = new CommunitySearch();
        $this->query = $search->buildQuery([], 'admin-all', false, $this->user_id);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconCommunity()
    {

        $search      = new CommunitySearch();
        $this->query = $search->buildQuery([], 'all', false, $this->user_id);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconCreatedByCommunities()
    {

        $search      = new CommunitySearch();
        $this->query = $search->buildQuery([], 'created-by', false, $this->user_id);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconMyCommunities()
    {

        $search      = new CommunitySearch();
        $this->query = $search->buildQuery([], 'own-interest', false, $this->user_id);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconMyCommunitiesWithTags()
    {

        $search      = new CommunitySearch();
        $this->query = $search->buildQuery([], 'own-interest-with-tags', false, $this->user_id);
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconToValidateCommunities()
    {
        $search      = new CommunitySearch();
        $this->query = $search->buildQuery([], 'to-validate', false, $this->user_id);
    }
}