<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\organizzazioni\i18n\grammar\ProfiloGroupsGrammar;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloGroups;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use Yii;
use yii\helpers\Url;

/**
 * Class ProfiloGroupsController
 * ProfiloGroupsController implements the CRUD actions for ProfiloGroups model.
 *
 * @property \open20\amos\organizzazioni\models\ProfiloGroups $model
 * @property \open20\amos\organizzazioni\models\search\ProfiloGroupsSearch $modelSearch
 *
 * @package open20\amos\organizzazioni\controllers\base
 */
class ProfiloGroupsController extends CrudController
{
    /**
     * Trait used for initialize the tab dashboard
     */
    use TabDashboardControllerTrait;
    
    /**
     * @var string $layout
     */
    public $layout = 'main';
    
    /**
     * @var Module|null $organizzazioniModule
     */
    public $organizzazioniModule = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->organizzazioniModule = Module::instance();
        
        $this->initDashboardTrait();
        
        $this->setModelObj($this->organizzazioniModule->createModel('ProfiloGroups'));
        $this->setModelSearch($this->organizzazioniModule->createModel('ProfiloGroupsSearch'));
        
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);
        
        parent::init();
        
        $this->setUpLayout();
    }
    
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        // TODO da pensare come rimuovere tutta sta roba facendo una classe o interfaccia fatta bene contanto di adeguamento delle viste nei layout.
        
        /** @var ProfiloGroupsGrammar $grammar */
        $grammar = $this->model->getGrammar();
        
        if (\Yii::$app->user->isGuest) {
            $titleSection = ucfirst($grammar->getModelLabel());
            $url = \Yii::$app->params['platform']['backendUrl'] . OrganizzazioniUtility::getLoginLink();
            $ctaLoginRegister = Html::a(
                Module::t('amosorganizzazioni', '#beforeActionCtaLoginRegister'),
                $url,
                [
                    'title' => Module::t('amosorganizzazioni', '#click_to_access_or_register', ['platformName' => \Yii::$app->name])
                ]
            );
            $subTitleSection = Html::tag('p',
                Module::t('amosorganizzazioni', '#beforeActionSubtitleSectionGuestGroups', [
                    'ctaLoginRegister' => $ctaLoginRegister
                ])
            );
        } else {
            $titleSection = ucfirst($grammar->getModelLabel());
            $subTitleSection = Html::tag('p', Module::t('amosorganizzazioni', '#beforeActionSubtitleSectionLogged'));
        }
        
        $labelCreate = Module::t('amosorganizzazioni', '#createLabelGroups');
        $titleCreate = Module::t('amosorganizzazioni', '#createTitleGroups');
        $labelManage = Module::t('amosorganizzazioni', '#manage');
        $titleManage = Module::t('amosorganizzazioni', '#manage') . ' ' . $grammar->getArticlePlural() . ' ' . strtolower($grammar->getModelLabel());
        $urlCreate = '/' . $this->model->getCreateUrl();
        $urlManage = null;
        
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'organizzazioni',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];
        
        if (!parent::beforeAction($action)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }
    
    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amosorganizzazioni', '#add_group')
        ];
    }
    
    /**
     * This method is useful to set all common params for all list views.
     * @param bool $setCurrentDashboard
     */
    protected function setListViewsParams($setCurrentDashboard = true)
    {
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
        if ($setCurrentDashboard && $this->hasMethod('getCurrentDashboard')) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        }
        Yii::$app->session->set(Module::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }
    
    /**
     * @param ProfiloGroups $model
     */
    public function getGroupOrganizationsQuery($model)
    {
        /** @var Profilo $profiloModel */
        $profiloModel = $this->organizzazioniModule->createModel('Profilo');
        $query = $model->getGroupProfilos();
        $query->orderBy([$profiloModel::tableName() . '.name' => SORT_ASC]);
        if ($this->organizzazioniModule->enableWorkflow) {
            $query->andWhere([$profiloModel::tableName() . '.status' => Profilo::PROFILO_WORKFLOW_STATUS_VALIDATED]);
        }
        return $query;
    }
    
    /**
     * Lists all models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(Module::t('amosorganizzazioni', '#organizations_groups'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }
        
        return parent::actionIndex();
    }
    
    /**
     * Displays a single model.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        Url::remember();
        $this->model = $this->findModel($id);
        return $this->render('view', ['model' => $this->model]);
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        
        $this->model = $this->getModelObj();
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save(false)) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully created.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'Element not created, check the data entered.'));
            }
        }
        
        return $this->render('create', [
            'model' => $this->model,
        ]);
    }
    
    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'list' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        
        $this->model = $this->findModel($id);
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save(false)) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully updated.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'Element not updated, check the data entered.'));
            }
        }
        
        return $this->render('update', [
            'model' => $this->model,
        ]);
    }
    
    /**
     * Deletes an existing model.
     * If deletion is successful, the browser will be redirected to the previous list page.
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $profiloGroupsMms = $this->model->profiloGroupsMms;
            $allOk = true;
            if (!empty($profiloGroupsMms)) {
                foreach ($profiloGroupsMms as $profiloGroupsMm) {
                    $profiloGroupsMm->delete();
                    if ($profiloGroupsMm->hasErrors()) {
                        $allOk = false;
                        break;
                    }
                }
            }
            if ($allOk) {
                $this->model->delete();
            }
            if ($allOk && !$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Module::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKey()));
    }
}
