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
use open20\amos\organizzazioni\Module;
use Yii;
use yii\helpers\Url;

/**
 * Class ProfiloSediController
 * ProfiloSediController implements the CRUD actions for ProfiloSedi model.
 *
 * @property \open20\amos\organizzazioni\models\ProfiloSedi $model
 * @property \open20\amos\organizzazioni\models\search\ProfiloSediSearch $modelSearch
 *
 * @package open20\amos\organizzazioni\controllers\base
 */
class ProfiloSediController extends CrudController
{
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
    
        $model = $this->organizzazioniModule->createModel('ProfiloSedi');
        $modelSearch = $this->organizzazioniModule->createModel('ProfiloSediSearch');

        $this->setModelObj($model);
        $this->setModelSearch($modelSearch);

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::tHtml('amoscore', 'Table')),
            'url' => '?currentView=grid'
        ];

        $this->setAvailableViews([
            'grid' => $this->viewGrid
        ]);

        parent::init();
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
            'createNewBtnLabel' => Module::t('amosorganizzazioni', '#add_headquarter')
        ];
    }

    /**
     * This method is useful to set all idea params for all list views.
     */
    protected function setListViewsParams()
    {
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
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
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        // TODO se sarà necessario mostrare la lista delle sedi, prefiltrate o meno, a qualcuno rimuovere il flash message e il redirect.
        Yii::$app->getSession()->addFlash('danger', Module::t('amosorganizzazioni', 'You cannot access the headquarters list directly'));
        return $this->redirect(['/organizzazioni/profilo/index']);

        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(Module::t('amosorganizzazioni', 'Headquarters'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }
        return parent::actionIndex();
    }

    /**
     * Displays a single model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int $profiloId
     * @return string|\yii\web\Response
     */
    public function actionCreate($profiloId)
    {
        $this->setUpLayout('form');

        $this->model = $this->organizzazioniModule->createModel('ProfiloSedi');
        $this->model->profilo_id = $profiloId;

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($this->model->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully created.'));
                    return $this->redirect(['update', 'id' => $this->model->id]);
                } else {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'Element not created, check the data entered.'));
                }
            } catch (\Exception $exception) {
                $transaction->rollBack();
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
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($this->model->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->addFlash('success', Module::t('amoscore', 'Element successfully updated.'));
                    return $this->redirect(['update', 'id' => $this->model->id]);
                } else {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', Module::t('amoscore', 'Element not updated, check the data entered.'));
                }
            } catch (\Exception $exception) {
                $transaction->rollBack();
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
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        $profiloId = $this->model->profilo_id;
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
        return $this->redirect(['/organizzazioni/profilo/update', 'id' => $profiloId]);
    }
}
