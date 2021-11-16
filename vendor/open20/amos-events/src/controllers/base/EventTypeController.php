<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\events\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use Yii;
use yii\helpers\Url;

/**
 * Class EventTypeController
 * EventTypeController implements the CRUD actions for EventType model.
 *
 * @property \open20\amos\events\models\EventType $model
 * @property \open20\amos\events\models\search\EventTypeSearch $modelSearch
 *
 * @package open20\amos\events\controllers\base
 */
class EventTypeController extends CrudController
{
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->eventsModule = AmosEvents::instance();

        $this->setModelObj($this->eventsModule->createModel('EventType'));
        $this->setModelSearch($this->eventsModule->createModel('EventTypeSearch'));

        EventsAsset::register(Yii::$app->view);

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosEvents::t('amosevents', '{tableIcon}' . Html::tag('p', AmosEvents::tHtml('amosevents', 'Table')), [
                    'tableIcon' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * Lists all EventType models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single EventType model.
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
     * Creates a new EventType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model = $this->eventsModule->createModel('EventType');

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element successfully created.'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not created, check the data entered.'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

    /**
     * Creates a new EventType model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $fid
     * @param $dataField
     * @return false|string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');

        $this->model = $this->eventsModule->createModel('EventType');

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                //Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element successfully created.'));
                return json_encode($this->model->toArray());
            } else {
                //Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not created, check the data entered.'));
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing EventType model.
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
                Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element succesfully updated.'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not updated, check the data entered.'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null,
        ]);
    }

    /**
     * Deletes an existing EventType model.
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
                Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element succesfully deleted.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amoscore', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }
}
