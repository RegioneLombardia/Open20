<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\controllers\base
 * @category   CategoryName
 */

namespace amos\sitemanagement\controllers\base;

use amos\sitemanagement\models\Metadata;
use amos\sitemanagement\models\search\MetadataSearch;
use amos\sitemanagement\Module;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use Yii;
use yii\helpers\Url;

/**
 * Class MetadataController
 * MetadataController implements the CRUD actions for Metadata model.
 *
 * @property \amos\sitemanagement\models\Metadata $model
 * @property \amos\sitemanagement\models\search\MetadataSearch $modelSearch
 *
 * @package amos\sitemanagement\controllers\base
 */
class MetadataController extends CrudController
{
    /**
     * Trait used for initialize the tab dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new Metadata());
        $this->setModelSearch(new MetadataSearch());

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
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amossitemanagement', 'Add metadata')
        ];
    }

    /**
     * This method is useful to set all idea params for all list views.
     */
    protected function setListViewsParams($setCurrentDashboard = true)
    {
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
//        if ($setCurrentDashboard) {
//            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
//        }
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
     * Lists all models.
     * @param string|null $layout
     * @return string
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(Module::t('amossitemanagement', 'Metadata'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }
        return parent::actionIndex();
    }

    /**
     * Displays a single model.
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model = new Metadata();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully created.'));
                return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKey()));
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
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully updated.'));
                return $this->redirect(Yii::$app->session->get(Module::beginCreateNewSessionKey()));
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
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
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
