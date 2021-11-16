<?php

/*
 * To change this proscription header, choose Proscription Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace open20\amos\community\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\community\AmosCommunity;
use Yii;
use yii\helpers\Url;
use open20\amos\community\utilities\CommunityUtil;


class WidgetHackathonCampusParty extends WidgetGraphic
{
    const ID_COMMUNITY_PADRE = 2751;
    const ID_COMMUNITY = 2754;
    
    
    /**
     * 
     * @return boolean
     */
    public function isVisible()
    {
        $ret = false;
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) 
        {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) 
            {
                if($scope['community'] == WidgetHackathonCampusParty::ID_COMMUNITY_PADRE)
                {
                    if (\Yii::$app->getUser()->can($this->getWidgetPermission())) {
                        $ret = true;
                    } 
                }
            }
        }
        return $ret;
    }
    
    
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('HACKATON_COMMUNITIES_GRAPHIC');
        $this->setLabel(AmosCommunity::t('amoscommunity', 'Hackathon'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'Consente all\'utente di accedre alla community Hackathon'));
    }

    /**
     * 
     * @return type
     */
    public function getHtml()
    {
        $viewToRender = 'hackathon_communities';
        if(CommunityUtil::userIsSignedUp(WidgetHackathonCampusParty::ID_COMMUNITY, \Yii::$app->user->id))
        {
            $url = Url::to(['/community/join', 'id' => WidgetHackathonCampusParty::ID_COMMUNITY]);
        }
        else
        {
            $url = Url::to(['/community/community/view', 'id' => WidgetHackathonCampusParty::ID_COMMUNITY]);
        }
        return $this->render($viewToRender, [
            'url' => $url,
            'widget' => $this,
        ]);
    }
}

