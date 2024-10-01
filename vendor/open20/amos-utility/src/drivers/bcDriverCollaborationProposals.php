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

use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\collaborations\models\search\CollaborationExpressionsOfInterestSearch;
use open20\amos\collaborations\models\search\CollaborationProposalsSearch;
use open20\amos\collaborations\models\CollaborationProposals;

/**
 *
 */
class bcDriverCollaborationProposals extends bcDriver
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName = CollaborationProposals::className(); // put here your model
        $this->widgetIconNames = [

        ];
    }

    public function searchWidgetIconCollaborationProposalsAll()
    {
        $this->query = $this->cwhActiveQuery->getQueryCwhAll();
    }

    /**
     *
     */
    public function searchWidgetIconCollaborationProposalsOwnInterest()
    {
        $this->query = $this->cwhActiveQuery->getQueryCwhOwnInterest();
    }

}
