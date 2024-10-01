<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\chrono\controllers\base
 */

namespace amos\chrono\controllers\base;

use amos\chrono\base\ChronoTaskStatus;
use amos\chrono\models\ChronoTask;
use amos\chrono\models\search\ChronoTaskSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\utilities\CurrentUser;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class ChronoTaskController
 * ChronoTaskController implements the CRUD actions for ChronoTask model.
 *
 * @property ChronoTask $model
 * @property ChronoTaskSearch $modelSearch
 *
 * @package amos\chrono\controllers\base
 */
class ChronoTaskController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new ChronoTask());
        $this->setModelSearch(new ChronoTaskSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p',
                    BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all ChronoTask models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnLabel();
        return $this->render(
                'index',
                [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : null,
                    'parametro' => ($this->parametro) ? $this->parametro : null,
                    'moduleName' => ($this->moduleName) ? $this->moduleName : null,
                    'contextModelId' => ($this->contextModelId) ? $this->contextModelId
                        : null,
                ]
        );
    }
    
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => BaseAmosModule::t('amoschrono', '#newtask'),
            'urlCreateNew' => [ '/chrono/chrono-task/create']
        ];
    }

    /**
     * Displays a single ChronoTask model.
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
     * Creates a new ChronoTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model          = new ChronoTask();
        $this->model->user_id = CurrentUser::getUser()->getId();
        $this->model->status  = ChronoTaskStatus::PENDING;
        $this->model->active = ChronoTaskStatus::ACTIVE;

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->next_run = $this->model->getNextRunDate();
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success',
                    Yii::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create',
                [
                    'model' => $this->model,
                    'fid' => NULL,
                    'dataField' => NULL,
                    'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new ChronoTask model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->model = new ChronoTask();

        if (Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post())
            && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return Json::encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax',
                [
                    'model' => $this->model,
                    'fid' => $fid,
                    'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing ChronoTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->next_run = $this->model->getNextRunDate();
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success',
                    Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update',
                [
                    'model' => $this->model,
                    'fid' => NULL,
                    'dataField' => NULL,
                    'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing ChronoTask model.
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
                Yii::$app->getSession()->addFlash('success',
                    BaseAmosModule::t('amoscore',
                        'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    BaseAmosModule::t('amoscore',
                        'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger',
                BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }
}