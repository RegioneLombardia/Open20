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
use open20\amos\community\models\search\CommunitySearch;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsMyCommunities
 * @package open20\amos\community\widgets\graphics
 */
class WidgetGraphicsCommunitiesRecommended extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setCode('COMMUNITIES_RECOMMENDED_GRAPHIC');
        $this->setLabel(AmosCommunity::t('amoscommunity', 'Communities Recommended'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'View the list of communities recommended'));
    }
    
    /**
     * @return string
     */
    public function getHtml()
    {
        $search = new CommunitySearch();
        $search->setNotifier(new NotifyWidgetDoNothing());
        
        $viewPath = '@vendor/open20/amos-community/src/widgets/graphics/views/';
        $viewToRender = $viewPath . 'communities_recommended';

        if(isset(\Yii::$app->params['WidgetGraphicsCommunitiesRecommended_limit']))
        {
            $numberToView = \Yii::$app->params['WidgetGraphicsCommunitiesRecommended_limit'];
        }else{
            $numberToView = 3;
        }
        if (is_null(\Yii::$app->getModule('layout'))) {
            $viewToRender .= '_old';
        }
        
        /** show subcommunities if you are inside a community and if you have anabled show subcommunities */
        $moduleCommunity = \Yii::$app->getModule('community');
        $showSubscommunities = $moduleCommunity->showSubcommunities;
        $linkToSubcommunities = false;
        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        if ($showSubscommunities) {
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (!is_null($moduleCwh)) {
                $scope = $moduleCwh->getCwhScope();
                if (!empty($scope) && isset($scope['community'])) {
                    $search->subcommunityMode = true;
                    $linkToSubcommunities = true;
                }
            }
        }
        
        $communitiesList = $search->searchCommunitiesRecommended($_GET, $numberToView);
       
        return $this->render(
            $viewToRender,
            [ 
                'communitiesList' => $communitiesList,
                'widget' => $this,
                'toRefreshSectionId' => 'widgetGraphicsCommunitiesRecommended',
                'linkToSubcommunities' => $linkToSubcommunities
            ]
        );
    }
}
