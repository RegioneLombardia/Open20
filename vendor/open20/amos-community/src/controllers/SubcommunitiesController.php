<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\controllers
 * @category   CategoryName
 */

namespace open20\amos\community\controllers;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\search\CommunitySearch;
use Yii;
use yii\helpers\Url;

/**
 * Class SubcommunitiesController
 * @package open20\amos\community\controllers
 */
class SubcommunitiesController extends CommunityController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setModelSearch(new CommunitySearch(['subcommunityMode' => true]));
    }
    
    /**
     * Base operations in order to render different list views
     * @return string
     */
    protected function baseListsAction($pageTitle, $layout = null)
    {
        Url::remember();
        $parentId = null;
        $parent = null;
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $parentId = $scope['community'];
            }
        }
        if(is_null($parentId)){
            $parentId = Yii::$app->request->getQueryParam('id');
        }
        $parent = Community::findOne($parentId);
        $can = is_null($parent) ? false : $parent->isCommunityManager();

        $urlCreation = ['/community/community-wizard/introduction', 'parentId' => $parentId];
        if (!Yii::$app->getModule('community')->enableWizard) {
            $urlCreation = ['/community/community/create', 'parentId' => $parentId];
        }
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosCommunity::tHtml('amoscommunity', '#btn_new_subcommunity'),
            'urlCreateNew' => $urlCreation
        ];
        if(!$can) {
            Yii::$app->view->params['createNewBtnParams']['checkPermWithNewMethod'] = true;
        }
        Yii::$app->session->set(AmosCommunity::beginCreateNewSessionKey(), Url::previous());
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->setListsBreadcrumbs($pageTitle);
        return $this->render('@vendor/open20/amos-community/src/views/community/index', [
            'enabledHierarchy' => false,
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ]);
    }
    
    /**
     * @inheritdoc
     */
    protected function setListsBreadcrumbs($pageTitle)
    {
        $translatedTitle = AmosCommunity::t('amoscommunity', $pageTitle);
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $parentId = $scope['community'];
                $communityParent = Community::findOne($parentId);
                if (isset($communityParent)) {
                    $translatedTitle = AmosCommunity::t('amoscommunity', '#subcommunities_in') . ' ' . $communityParent->name;
                }
            }
        }
        Yii::$app->view->title = $translatedTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $translatedTitle],
        ];
    }
}
