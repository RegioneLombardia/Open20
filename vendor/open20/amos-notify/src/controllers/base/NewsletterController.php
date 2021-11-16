<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\controllers\base
 */

namespace open20\amos\notificationmanager\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\notificationmanager\AmosNotify;
use Yii;
use yii\helpers\Url;

/**
 * Class NewsletterController
 * NewsletterController implements the CRUD actions for Newsletter model.
 *
 * @property \open20\amos\notificationmanager\models\Newsletter $model
 * @property \open20\amos\notificationmanager\models\search\NewsletterSearch $modelSearch
 *
 * @package open20\amos\notificationmanager\controllers\base
 */
class NewsletterController extends CrudController
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
     * @var AmosNotify $notifyModule
     */
    public $notifyModule = null;
    
    /**
     * @var string $btnAssociaLabelPrefix
     */
    protected $btnAssociaLabelPrefix = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->notifyModule = AmosNotify::instance();

        $this->initDashboardTrait();

        $this->setModelObj($this->notifyModule->createModel('Newsletter'));
        $this->setModelSearch($this->notifyModule->createModel('NewsletterSearch'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();

        $this->setUpLayout();
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
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosNotify::t('amosnotify', '#add_newsletter')
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
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        }
        Yii::$app->session->set(AmosNotify::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosNotify::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * This method returns the close url for the action view.
     * @return mixed|string|null
     */
    public function getUrlClose()
    {
        $sessionUrl = Yii::$app->session->get(AmosNotify::beginCreateNewSessionKey());
        return ($sessionUrl ? $sessionUrl : Url::previous());
    }
    
    /**
     * Base operations for list views
     * @param string $pageTitle
     * @return string
     */
    protected function baseListsAction($pageTitle, $setCurrentDashboard = true)
    {
        Url::remember();
        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams($setCurrentDashboard);
        $renderParams = [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ];
        return $this->render('index', $renderParams);
    }
    
    /**
     * This method returns to the correct redirect action
     * @return \yii\web\Response
     */
    protected function baseActionsRedirect()
    {
        $sessionUrl = Yii::$app->session->get(AmosNotify::beginCreateNewSessionKey());
        if ($sessionUrl) {
            return $this->redirect($sessionUrl);
        }
        $urlPrevious = Url::previous();
        if (!is_null($urlPrevious)) {
            return $this->redirect($urlPrevious);
        }
        return $this->redirect(['index']);
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
        $this->setTitleAndBreadcrumbs(AmosNotify::t('amosnotify', 'Tutte le newsletter'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }

        return parent::actionIndex();
    }

    /**
     * Displays a single Newsletter model.
     * @param integer $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        return $this->render('view', ['model' => $this->model]);
    }

    /**
     * Creates a new Newsletter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model = $this->notifyModule->createModel('Newsletter');

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
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
     * Updates an existing Newsletter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing Newsletter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
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
}
