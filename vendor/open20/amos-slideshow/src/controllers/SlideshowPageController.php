<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow\controllers
 * @category   CategoryName
 */

namespace open20\amos\slideshow\controllers;

use open20\amos\slideshow\AmosSlideshow;
use open20\amos\slideshow\controllers\base\SlideshowPageController as BaseSlideshowPageController;
use open20\amos\slideshow\models\Slideshow;
use open20\amos\slideshow\models\SlideshowPage;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * Class SlideshowPageController
 *
 * This is the class for controller "SlideshowPageController".
 *
 * @package open20\amos\slideshow\controllers
 */
class SlideshowPageController extends BaseSlideshowPageController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className(),
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'create-for-specific-slideshow',
                            ],
                            'roles' => [strtoupper(StringHelper::basename(SlideshowPage::className())).'_CREATE',
                                'ADMIN']
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
    
    public function beforeAction($action) {
        $urlLinkAll = '/slideshow/slideshow/index';
        
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->view->params = [
            'isGuest' => Yii::$app->user->isGuest,
            'modelLabel' => 'Slideshow',
            'titleSection' => AmosSlideshow::t('amosslideshow', 'Tutte le slides'),
            'subTitleSection' => null,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => AmosSlideshow::t('amosslideshow', 'Tutti gli slideshow'),
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => AmosSlideshow::t('amosslideshow', 'Nuova'),
            'titleCreate' => AmosSlideshow::t('amosslideshow', 'Aggiungi slide'),
            'labelManage' => AmosSlideshow::t('amosslideshow', 'Gestisci'),
            'titleManage' => AmosSlideshow::t('amosslideshow', 'Gestisci slide'),
        ];

        // other custom code here
        return true;
    }

    /**
     * Lists all SlideshowPage models.
     * @param null|string $layout
     * @return mixed|string
     */
    public function actionIndex($layout = NULL)
    {
        $this->setUpLayout('list');

        Url::remember();
        $params = Yii::$app->request->getQueryParams();
        $this->setDataProvider($this->getModelSearch()->searchSlideshowPages($params));

        //Yii::$app->view->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Slideshow'), 'url' => '/slideshow/slideshow/index'];
        $slideshowName = "";
        if (isset($params['slideshowId'])) {
            Yii::$app->view->params['slideshowId'] = $params['slideshowId'];
            $slideshow                             = Slideshow::findOne($params['slideshowId']);
            if (!is_null($slideshow)) {
                $slideshowName = ": ".$slideshow->name;
            }
            Yii::$app->view->title = AmosSlideshow::t('amosslideshow',
                    'Pagine dello slideshow'.$slideshowName);
        } else {
            Yii::$app->view->title = AmosSlideshow::t('amosslideshow',
                    'Pagine degli slideshow');
        }
        //Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;
        Yii::$app->view->params['createNewBtnParams'] = [
            'urlCreateNew' => [
                'create-for-specific-slideshow',
                'slideshow_id' => $params['slideshowId']
            ]
        ];

        return $this->render('index',
                [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : NULL,
                'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Creates a new SlideshowPage model for a specific slideshow.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateForSpecificSlideshow()
    {
        $this->setUpLayout('form');

        $this->model               = new SlideshowPage();
        $params                    = Yii::$app->request->getQueryParams();
        $this->model->slideshow_id = $params['slideshow_id'];

        Yii::$app->view->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow',
                'Slideshow'), 'url' => '/slideshow'];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow',
                'Elenco'), 'url' => '/slideshow/slideshow/index'];
        $slideshowPageName                       = 'Pagine degli slideshow';
        if (isset($params['slideshow_id'])) {
            $slideshow = Slideshow::findOne($params['slideshow_id']);
            if (!is_null($slideshow)) {
                $slideshowName = $slideshow->name;
            }
        }
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $slideshowName, 'url' => Url::previous()];
        Yii::$app->view->title                   = AmosSlideshow::t('amosslideshow',
                'Crea pagina');
        Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success',
                    AmosSlideshow::tHtml('amosslideshow',
                        'Elemento creato correttamente.'));
                return $this->redirect(Url::previous());
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    AmosSlideshow::tHtml('amosslideshow',
                        'Elemento non creato, verificare i dati inseriti.'));
                return $this->render('create',
                        [
                        'model' => $this->model,
                        'slideshowName' => $slideshowName
                ]);
            }
        } else {
            return $this->render('create',
                    [
                    'model' => $this->model,
                    'slideshowName' => $slideshowName
            ]);
        }
    }
}