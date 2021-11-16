<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\userimporter\controllers\base
 */

namespace backend\modules\userimporter\controllers\base;

use backend\modules\userimporter\base\UserImportTaskStatus;
use backend\modules\userimporter\models\search\UserImportTaskSearch;
use backend\modules\userimporter\models\UserImportTask;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\utilities\CurrentUser;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use open20\amos\events\utility\EventsUtility;

/**
 * Class UserImporterTaskController 
 * UserImporterTaskController implements the CRUD actions for UserImportTask model.
 *
 * @property UserImportTask $model
 * @property UserImportTaskSearch $modelSearch
 *
 * @package backend\modules\userimporter\controllers\base 
 */
class UserImportTaskController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new UserImportTask());
        $this->setModelSearch(new UserImportTaskSearch());

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
     * Lists all UserImportTask models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        \Yii::$app->view->params['customClassMainContent'] = 'box-container';
        \Yii::$app->view->params['enableLayoutList'] = true;
        \Yii::$app->view->params['createNewBtnParams'] = ['layout' => ''];
        
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
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

    /**
     * Displays a single UserImportTask model.
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
     * Creates a new UserImportTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model                          = new UserImportTask();
        $this->model->task_date               = Yii::$app->formatter->asDate('now','php:Y-m-d H:i:s');
        $this->model->user_id                 = CurrentUser::getUser()->getId();
        $this->model->status                  = UserImportTaskStatus::PENDING;

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success',
                    Yii::t('amoscore', 'Item created'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Yii::t('amoscore', 'Item not created, check data'));
            }
        }
//        else {
//            pr($this->model->errors);
//        }

        return $this->render('create',
                [
                    'model' => $this->model,
                    'fid' => NULL,
                    'dataField' => NULL,
                    'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new UserImportTask model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->model = new UserImportTask();

        if (Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post())
            && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
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
     * Updates an existing UserImportTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
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
     * Deletes an existing UserImportTask model.
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