<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics
 * @category   CategoryName
 */

namespace open20\amos\community\widgets\graphics;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsCommunityReports
 * @package open20\amos\community\widgets\graphics
 */
class WidgetGraphicsCommunityReports extends WidgetGraphic
{
    /**
     * @var null|int $communityId
     */
    private $communityId = null;
    
    /**
     * @var null|Community $community
     */
    private $community = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setCode('COMMUNITY_REPORTS_GRAPHIC');
        $this->setLabel(AmosCommunity::t('amoscommunity', 'Reports'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'Download reports'));
    }
    
    /**
     * Returns the community id
     * @return int
     */
    public function getCommunityId()
    {
        return $this->communityId;
    }
    
    /**
     * @inheritdoc
     */
    public function isVisible()
    {
        // The logged user hasn't permission to view this widget.
        if (!parent::isVisible()) {
            return false;
        }
        
        // If there's no CWH module in the application the widget can be viewed.
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (is_null($moduleCwh)) {
            return true;
        }
        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        
        $ok = true;
        $scope = $moduleCwh->getCwhScope();
        if (!empty($scope) && isset($scope[Community::tableName()])) {
            $communityId = $scope[Community::tableName()];
            $this->community = Community::findOne($communityId);
            if (is_null($this->community)) {
                // Have CWH scope and community object to null means the CWH scope is not correct.
                // It's an error! The method must return false!
                $ok = false;
            } else {
                $this->communityId = $communityId;
            }
        }
        
        return $ok;
    }
    
    /**
     * @inheritdoc
     */
    public function getHtml()
    {
        $search = new CommunitySearch();
        $search->setNotifier(new NotifyWidgetDoNothing());
        
        $viewToRender = 'community_reports';
        
        if (is_null(\Yii::$app->getModule('layout'))) {
            $viewToRender = 'community_reports_old';
        }
        
        return $this->render($viewToRender, [
            'widget' => $this,
            'downloadConfs' => $this->makeDownloadConfs(),
        ]);
    }
    
    /**
     * @return array
     */
    private function makeDownloadConfs()
    {
        $statisticsModule = \Yii::$app->getModule('statistics');
        $nullStatisticsModule = (is_null($statisticsModule));
        return [
            [
                'text' => AmosCommunity::t('amoscommunity', 'Uploaded files size'),
                'url' => (!is_null($this->community) ? ['/community/reports/uploaded-files-size', 'id' => $this->communityId] : ['/community/reports/all-uploaded-files-size']),
                'hideThis' => $nullStatisticsModule
            ],
            [
                'text' => AmosCommunity::t('amoscommunity', 'Contacts / Users List'),
                'url' => (!is_null($this->community) ? ['/community/reports/contacts-list', 'id' => $this->communityId] : ['/community/reports/all-contacts-list'])
            ],
            [
                'text' => AmosCommunity::t('amoscommunity', 'Documents types'),
                'url' => (!is_null($this->community) ? ['/community/reports/documents-types', 'id' => $this->communityId] : ['/community/reports/all-documents-types']),
                'hideThis' => $nullStatisticsModule
            ],
            [
                'text' => AmosCommunity::t('amoscommunity', 'Access frequency'),
                'url' => (!is_null($this->community) ? ['/community/reports/access-frequency', 'id' => $this->communityId] : ['/community/reports/all-access-frequency'])
            ]
        ];
    }
}
