<?php

namespace amos\sitemanagement\controllers\base;

use amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingFather;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use Yii;
use amos\sitemanagement\models\SiteManagementLandingPubblication;
use amos\sitemanagement\models\search\SiteManagementLandingPubblicationSearch;
use open20\amos\core\controllers\CrudController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;
use amos\sitemanagement\Module;

/**
 * SiteManagementLandingPubblicationController implements the CRUD actions for SiteManagementLandingPubblication model.
 */
class SiteManagementLandingPubblicationController extends CrudController
{
    use TabDashboardControllerTrait;

    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new SiteManagementLandingPubblication());
        $this->setModelSearch(new SiteManagementLandingPubblicationSearch());

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
            'createNewBtnLabel' => Module::t('amossitemanagement', 'Add element')
        ];
    }

    /**
     * This method is useful to set all idea params for all list views.
     */
    protected function setListViewsParams($setCurrentDashboard = true)
    {
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
            $this->child_of = WidgetIconSiteManagementLandingFather::className();

        }
        Yii::$app->session->set(Module::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(Module::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * This method returns the close url for close button in action view.
     * @return string
     */
    public function getViewCloseUrl()
    {
        return Yii::$app->session->get(Module::beginCreateNewSessionKey());
    }

    /**
     * @param null $layout
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(Module::t('amossitemanagement', 'Elements'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }
        return parent::actionIndex();
    }

    /**
     * Displays a single SiteManagementLandingPubblication model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new SiteManagementLandingPubblication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout("form");
        $model = new SiteManagementLandingPubblication;
        $smRoutes = new \amos\sitemanagement\models\SiteManagmentLandingRoutes();
        $routes = $smRoutes->getRoutes();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
                return $this->render('create', [
                    'model' => $model,
                    'routes' => $routes
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'routes' => $routes

            ]);
        }
    }

    /**
     * Updates an existing SiteManagementLandingPubblication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout("form");
        $model = $this->findModel($id);
        $smRoutes = new \amos\sitemanagement\models\SiteManagmentLandingRoutes();
        $routes = $smRoutes->getRoutes();


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
                return $this->render('update', [
                    'model' => $model,
                    'routes' => $routes

                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'routes' => $routes

            ]);
        }
    }

    /**
     * Deletes an existing SiteManagementLandingPubblication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
//si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva 
//In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
//la cancellazione del record in presenza di foreign key quindi 
//il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
//e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
//In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà 
//mai a buon fine in caso di dipendenze presenti sul record da cancellare
            if ($model->delete()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
        }
        return $this->redirect(['index']);
    }
}
