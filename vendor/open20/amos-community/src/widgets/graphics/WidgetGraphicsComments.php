<?php
/**
 * Aria S.p.A.
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
 * Class WidgetGraphicsComments
 * @package open20\amos\community\widgets\graphics
 */
class WidgetGraphicsComments extends WidgetGraphic
{
    private $community, $refresh, $numberToView;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setDefaultValues();
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $viewPath     = '@vendor/open20/amos-community/src/widgets/graphics/views/';
        $viewToRender = $viewPath.'comments';
    
       // $commentList = $search->searchMyCommunities($_GET, $this->getNumberToView());

        return $this->render( 
                $viewToRender,
                [
                'widget' => $this,                
                ]
        );
    }

    /**
     *
     * @return integer
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     *
     * @param integer $community
     */
    public function setCommunity($community)
    {
        $this->community = $community;
    }

    /**
     *
     * @return integer
     */
    public function getRefresh()
    {
        return $this->refresh;
    }

    /**
     *
     * @param integer $refresh
     */
    public function setRefresh($refresh)
    {
        $this->refresh = $refresh;
    }

    /**
     *
     * @return integer
     */
    public function getNumberToView(){
        return $this->numberToView;
    }

    /**
     *
     * @param integer $numberToView
     */
    public function setNumberToView($numberToView){
        $this->numberToView = $numberToView;
    }

    public function setDefaultValues()
    {
        $this->setCode('COMMENTSCOMMUNITY_GRAPHIC');
        $this->setLabel(AmosCommunity::t('amoscommunity', 'Comments'));
        $this->setDescription(AmosCommunity::t('amoscommunity', 'View the list of my communities'));
        if (empty($this->getCommunity())) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (!empty($moduleCwh)) {
                $scope = $moduleCwh->getCwhScope();
                if (!empty($scope) && isset($scope['community'])) {
                    $this->setCommunity($scope['community']);
                }
            }
        }
        $moduleCommunity = \Yii::$app->getModule(AmosCommunity::getModuleName());
        if (empty($this->getRefresh())) {
            $this->setRefresh($moduleCommunity->refreshWidgetGraphicsComments);
        }
        if(empty($this->getNumberToView())){
            $this->setNumberToView($moduleCommunity->numberToViewWidgetGraphicsComments);
        }
    }

    public function isVisible()
    {
        return true;
    }
}