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
use open20\amos\een\models\EenPartnershipProposal;
use open20\amos\een\models\search\EenPartnershipProposalSearch;
use open20\amos\een\widgets\icons\WidgetIconEen;
use open20\amos\een\widgets\icons\WidgetIconEenAll;
use open20\amos\een\widgets\icons\WidgetIconEenArchived;
use open20\amos\een\widgets\icons\WidgetIconEenExprOfInterest;
use open20\amos\een\widgets\icons\WidgetIconEenExprOfInterestAll;
use open20\amos\een\widgets\icons\WidgetIconEenExprOfInterestReceived;

/**
 * 
 */
class bcDriverEen extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = EenPartnershipProposal::className(); // put here your model
        $this->widgetIconNames = [
//            WidgetIconEen::getWidgetIconName() => WidgetIconEen::classname(),
//            WidgetIconEenAll::getWidgetIconName() => WidgetIconEenAll::classname(),
//            WidgetIconEenArchived::getWidgetIconName() => WidgetIconEenArchived::classname(),
//            WidgetIconEenExprOfInterest::getWidgetIconName() => WidgetIconEenExprOfInterest::classname(),
//            WidgetIconEenExprOfInterestAll::getWidgetIconName() => WidgetIconEenExprOfInterestAll::classname(),
//            WidgetIconEenExprOfInterestReceived::getWidgetIconName() => WidgetIconEenExprOfInterestReceived::classname(),
        ];
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEen()
    {
        $this->query = $this->cwhActiveQuery->getQueryCwhOwnInterest();
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEenAll()
    {
        $search      = new EenPartnershipProposalSearch();
        $this->query = $search->buildQuery([], 'all');
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEenArchived()
    {
        $search      = new EenPartnershipProposalSearch();
        $this->query = $search->buildQuery([], 'archived');
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEenExprOfInterest()
    {
        $search      = new EenPartnershipProposalSearch();
        $this->query = $search->buildQuery([], 'all');
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEenExprOfInterestAll()
    {
        $search      = new EenPartnershipProposalSearch();
        $this->query = $search->buildQuery([], 'all');
    }

    /**
     * Put here your search queries
     */
    public function searchWidgetIconEenExprOfInterestReceived()
    {
        $search      = new EenPartnershipProposalSearch();
        $this->query = $search->buildQuery([], 'all');
    }
}