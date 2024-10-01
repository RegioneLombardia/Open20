<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers
 * @category   CategoryName
 */

namespace open20\amos\events\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\events\AmosEvents;
use Yii;
use yii\helpers\Url;

/**
 * Class EventRoomController
 * EventRoomController implements the CRUD actions for EventRoom model.
 *
 * @property \open20\amos\events\models\EventRoom $model
 *
 * @package open20\amos\events\controllers\base
 */
class EventRoomController extends CrudController
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
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->eventsModule = AmosEvents::instance();

        $this->initDashboardTrait();

        $this->setModelObj($this->eventsModule->createModel('EventRoom'));
        $this->setModelSearch($this->eventsModule->createModel('EventRoomSearch'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosEvents::t('amosevents', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();

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
            'createNewBtnLabel' => AmosEvents::t('amosevents', 'Create room')
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
        Yii::$app->session->set(AmosEvents::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosEvents::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
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

        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosEvents::t('amosevents', 'Event Rooms'));
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
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $this->model = $this->eventsModule->createModel('EventRoom');

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element successfully created.'));
                return $this->redirect(Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey()));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not created, check the data entered.'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null
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
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element successfully updated.'));
                return $this->redirect(Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey()));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not updated, check the data entered.'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => null,
            'dataField' => null,
            'dataEntity' => null
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
            if (!$this->model->events) {
                $this->model->delete();
                if (!$this->model->hasErrors()) {
                    Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element deleted successfully.'));
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'You are not authorized to delete this element.'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', '#cannot_delete_event_toom'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not found.'));
        }
        return $this->redirect(Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey()));
    }
}
