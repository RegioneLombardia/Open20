<?php

/**
 * Aria S.p.A.
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
use open20\amos\community\widgets\icons\WidgetIconAdminAllCommunity;
use open20\amos\community\widgets\icons\WidgetIconToValidateCommunities;
use Yii;
use yii\helpers\Url;
use open20\amos\core\helpers\Html;


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
        if (is_null($parentId)) {
            $parentId = Yii::$app->request->getQueryParam('id');
        }
        $parent = Community::findOne($parentId);
        $can = is_null($parent) ? false : $parent->isCommunityManager();

        $urlCreation = ['/community/community-wizard/introduction', 'parentId' => $parentId];
        if (!Yii::$app->getModule('community')->enableWizard) {
            $urlCreation = ['/community/community/create', 'parentId' => $parentId];
        }
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosCommunity::tHtml('amoscommunity', 'Nuova'),
            'urlCreateNew' => $urlCreation
        ];
        // Yii::$app->view->params['titleSection'] = AmosCommunity::tHtml('amoscommunity', 'Sottocommunity');
        // Yii::$app->view->params['urlLinkAll'] = '';
        // Yii::$app->view->params['subTitleSection'] = Html::tag(
        //     'p',
        //     AmosCommunity::t(
        //         'amoscommunity',
        //         '#here_you_can_find_the',
        //         ['parent_community' => $parent->name]
        //     )
        // );

        if (!$can) {
            Yii::$app->view->params['createNewBtnParams']['checkPermWithNewMethod'] = true;
            Yii::$app->view->params['canCreate'] = false;
            Yii::$app->view->params['titleScopePreventCreate'] = AmosCommunity::t('amoscommunity', "Non hai il permesso per creare sottocommunity");

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

    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {

        if (get_class(Yii::$app->controller) != 'open20\amos\community\controllers\CommunityController') {
            $links[] = [
                'title' => AmosCommunity::t('amoscommunity', 'Visualizza tutte le community'),
                'label' => AmosCommunity::t('amoscommunity', 'Tutte'),
                'url' => '/community/subcommunities/index'
            ];
        }

        $links[] = [
            'title' => AmosCommunity::t('amoscommunity', 'Visualizza le community create da me'),
            'label' => AmosCommunity::t('amoscommunity', 'Create da me'),
            'url' => '/community/subcommunities/created-by-communities'
        ];

        $links[] = [
            'title' => AmosCommunity::t('amoscommunity', 'Le mie community'),
            'label' => AmosCommunity::t('amoscommunity', 'Le mie community'),
            'url' => '/community/subcommunities/my-communities'
        ];

        if (\Yii::$app->user->can(WidgetIconToValidateCommunities::class)) {
            $links[] = [
                'title' => AmosCommunity::t('amoscommunity', 'Visualizza community da validare'),
                'label' => AmosCommunity::t('amoscommunity', 'Da validare'),
                'url' => '/community/subcommunities/to-validate-communities'
            ];
        }

        if (\Yii::$app->user->can(WidgetIconAdminAllCommunity::class)) {
            $links[] = [
                'title' => AmosCommunity::t('amoscommunity', 'Amministra tutte le community'),
                'label' => AmosCommunity::t('amoscommunity', 'Amministra'),
                'url' => '/community/subcommunities/admin-all-communities'
            ];
        }

        return $links;
    }

}
