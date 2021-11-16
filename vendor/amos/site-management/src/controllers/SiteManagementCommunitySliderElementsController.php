<?php

namespace amos\sitemanagement\controllers;

use amos\sitemanagement\models\search\SiteManagementSliderSearch;
use amos\sitemanagement\models\SiteManagementCommunitySliderMm;
use amos\sitemanagement\models\SiteManagementSlider;
use amos\sitemanagement\widgets\SMContainerWidget;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use amos\sitemanagement\Module;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;

/**
 * This is the class for controller "SiteManagementCommunitySliderElementsController".
 */
class SiteManagementCommunitySliderElementsController extends CrudController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'images',
                            'videos',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        // $this->initDashboardTrait();

        $this->setModelObj(new SiteManagementSlider());
        $this->setModelSearch(new SiteManagementSliderSearch());

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::tHtml('amoscore', 'Table')),
            'url' => '?currentView=grid'
        ];

        $this->setAvailableViews([
            'grid' => $this->viewGrid
        ]);

        parent::init();

        $this->layout = 'main';
        $this->setUpLayout();
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImages()
    {
        Url::remember();
        // $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));

        $communityId = null;
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
            }
        }

        if (is_null($communityId)) {
            throw new ForbiddenHttpException(Module::t('amoscore', 'Community not is set'));
        }

        $slider = $this->getSlider($communityId, SiteManagementCommunitySliderMm::TYPE_IMAGES);

        $dataProviderSliderElem = new ActiveDataProvider(['query' => $slider->getSliderElems()->orderBy('order ASC')]);

        // VarDumper::dump( $dataProviderSliderElem, $depth = 3, $highlight = true);


        return $this->render('images', [
            'model' => $slider,
            'dataProvider' => $dataProviderSliderElem,
        ]);
    }
    
    
    /**
    * @param $id
    * @return array
    * @throws \yii\web\NotFoundHttpException
    */
   public function actionVideos()
   {
       Url::remember();
       // $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));

       $communityId = null;
       $moduleCwh = \Yii::$app->getModule('cwh');
       if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
           $scope = $moduleCwh->getCwhScope();
           if (isset($scope['community'])) {
               $communityId = $scope['community'];
           }
       }

       if (is_null($communityId)) {
           throw new ForbiddenHttpException(Module::t('amoscore', 'Community not is set'));
       }

       $slider = $this->getSlider($communityId, SiteManagementCommunitySliderMm::TYPE_VIDEOS);

       $dataProviderSliderElem = new ActiveDataProvider(['query' => $slider->getSliderElems()->orderBy('order ASC')]);

       // VarDumper::dump( $dataProviderSliderElem, $depth = 3, $highlight = true);


       return $this->render('videos', [
           'model' => $slider,
           'dataProvider' => $dataProviderSliderElem,
       ]);
   }

    /**
     * If not exixt the slider is created
     *
     * @param integer $communityId
     * @param string $context
     * @return SiteManagementSlider
     */
    private function getSlider($communityId, $context)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $communitySlider = SiteManagementCommunitySliderMm::find()
            ->andWhere(['community_id' => $communityId])
            ->andWhere(['type' => $context])
            ->one();

        if (empty($communitySlider)) {
            try {
                $communitySlider = new SiteManagementCommunitySliderMm();
                $communitySlider->community_id = $communityId;
                $communitySlider->type = $context;
                $slider = new SiteManagementSlider();
                $slider->save(false);

                $communitySlider->site_management_slider_id = $slider->id;
                $communitySlider->save(false);

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new ForbiddenHttpException(Module::t('amoscore', 'Unable to create slider'));
            }
        }

        return $communitySlider->siteManagementSlider;
    }
}
