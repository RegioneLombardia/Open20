<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\podcast\controllers\base
 */

namespace amos\podcast\controllers\base;

use amos\podcast\Module;
use amos\podcast\widgets\icons\WidgetIconDashboardPodcast;
use open20\amos\admin\AmosAdmin;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use Yii;
use amos\podcast\models\PodcastCategory;
use amos\podcast\models\search\PodcastCategorySearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;


/**
 * Class PodcastCategoryController
 * PodcastCategoryController implements the CRUD actions for PodcastCategory model.
 *
 * @property \amos\podcast\models\PodcastCategory $model
 * @property \amos\podcast\models\search\PodcastCategorySearch $modelSearch
 *
 * @package amos\podcast\controllers\base
 */
class PodcastCategoryController extends CrudController
{
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new PodcastCategory());
        $this->setModelSearch(new PodcastCategorySearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),         
                'url' => '?currentView=list'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),           
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),      
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),                                            
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $subTitleSection  = Html::tag('p', AmosAdmin::t('amosadmin', ''));
            $ctaLoginRegister = Html::a(
                AmosAdmin::t('amosadmin', 'registrati alla piattaforma'),
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'].'/'.AmosAdmin::getModuleName().'/security/login',
                [
                    'title' => AmosAdmin::t('amosadmin',
                        'Clicca per accedere o registrarti alla piattaforma {platformName}',
                        ['platformName' => \Yii::$app->name])
                ]
            );
            $subTitleSection  .= Html::tag('p',
                AmosAdmin::t('amosadmin',
                    'Unisciti a {platformName}! La piattaforma nata per rafforzare la capacità di governance e per migliorare la competitività del sistema lombardo della conoscenza, {ctaLoginRegister}',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]));
        }

        $labelCreate = Module::t('amospodcast', 'Nuovo');
        $titleCreate = Module::t('amospodcast', 'Crea nuova categoria');
        $labelManage = Module::t('amospodcast', 'Gestisci');
        $titleManage = Module::t('amospodcast', 'Gestisci i podcast');
        $urlCreate   = Module::t('amospodcast', '/podcast/podcast-category/create');
        $urlManage   = Module::t('amospodcast', '#');

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'podcasts',
            'labelCreate' => $labelCreate,
            'subTitleSection' => $subTitleSection,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * Lists all PodcastCategory models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        $this->view->title = Module::t('amospodcast', "Categorie podcast");
        $this->view->params['titleSection'] = $this->view->title;
        Url::remember();
        $this->setIndexParams();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single PodcastCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new PodcastCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new PodcastCategory();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new PodcastCategory model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new PodcastCategory();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing PodcastCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing PodcastCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    public function setIndexParams()
    {
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->child_of = WidgetIconDashboardPodcast::className();
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        return \amos\podcast\controllers\PodcastController::getManageLinks();
    }
}
