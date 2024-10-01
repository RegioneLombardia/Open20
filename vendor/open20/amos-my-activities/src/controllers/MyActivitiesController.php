<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\controllers
 * @category   CategoryName
 */

namespace open20\amos\myactivities\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\myactivities\AmosMyActivities;
use open20\amos\myactivities\models\MyActivities;
use open20\amos\myactivities\models\search\MyActivitiesModelSearch;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class MyActivitiesController
 * MyActivitiesController implements the CRUD actions
 *
 * @property \open20\amos\myactivities\models\MyActivities $model
 * @property \open20\amos\myactivities\models\MyActivities $modelSearch
 *
 * @package open20\amos\myactivities\controllers
 */
class MyActivitiesController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @var AmosMyActivities $myActivitiesModule
     */
    public $myActivitiesModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->myActivitiesModule = \Yii::$app->getModule(AmosMyActivities::getModuleName());

        $this->setModelObj($this->myActivitiesModule->createModel('MyActivities'));
        $this->setModelSearch($this->myActivitiesModule->createModel('MyActivities'));

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', AmosMyActivities::t('amoscore', 'List')),
                'url' => '?currentView=list'
            ],
        ]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'index',
                            ],
                            'roles' => ['ADMIN', 'VALIDATED_BASIC_USER']
                        ],
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]
        );
    }

    public function beforeAction($action)
    {

        $titleSection = AmosMyActivities::t('amosmyactivities', 'Le mie attività');
        $subTitleSection = Html::tag('p', AmosMyActivities::t('amosmyactivities', ''));

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'myactivities',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'hideCreate' => true,
            'hideManage' => true
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * @param string|null $layout
     * @return string
     */
    public function actionIndex($layout = NULL)
    {
        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $moduleCwh->resetCwhScopeInSession();
        }

        Url::remember();
        $this->setUpLayout('list');

        $modelSearch = new MyActivitiesModelSearch;
        $modelSearch->load(\Yii::$app->request->getQueryParams());

        /** @var MyActivities $model */
        $model            = $this->myActivitiesModule->createModel('MyActivities');
        $listOfActivities = $model->getMyActivities($modelSearch);

        if (class_exists('open20\\amos\\utility\\drivers\\bcDriverMyActivities')) {
            // TBD, update counter for widget
            \open20\amos\utility\drivers\bcDriverMyActivities::updateBulletCountersTable(
                \Yii::$app->getUser()->getId(),
                AmosMyActivities::getModuleName(),
                'open20\amos\myactivities\widgets\icons\WidgetIconMyActivities',
                count($listOfActivities),
                true
            );
        }

        $dataProvider = new ArrayDataProvider();
        if (count($listOfActivities) > 0) {
            $dataProvider->setModels($listOfActivities);
            $this->parametro['empty'] = false;
        } else {
            $dataProvider->setModels([]);
            $this->parametro['empty'] = true;
        }
        $this->dataProvider = $dataProvider;

        $this->setModelSearch($modelSearch);
        
        $this->view->title = AmosMyActivities::t('amosmyactivities', 'My activities');

        return parent::actionIndex();
    }
}
