<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers\base
 */

namespace open20\amos\events\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventCalendars;
use open20\amos\events\models\search\EventCalendarsSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;


/**
 * Class EventCalendarsController
 * EventCalendarsController implements the CRUD actions for EventCalendars model.
 *
 * @property \open20\amos\events\models\EventCalendars $model
 * @property \open20\amos\events\models\search\EventCalendarsSearch $modelSearch
 *
 * @package open20\amos\events\controllers\base
 */
class EventCalendarsController extends CrudController
{
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

        $this->setModelObj($this->eventsModule->createModel('EventCalendars'));
        $this->setModelSearch($this->eventsModule->createModel('EventCalendarsSearch'));

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

    /**
     * Lists all EventCalendars models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single EventCalendars model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);
        $event = $this->model->event;
        $dataProviderSlots = new ActiveDataProvider([
            'query' => $this->model->getEventCalendarsSlots()
        ]);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', [
                'model' => $this->model,
                'dataProviderSlots' => $dataProviderSlots,
                'event' => $event
            ]);
        }
    }

    /**
     * Creates a new EventCalendars model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $this->setUpLayout('form');

        $this->model = $this->eventsModule->createModel('EventCalendars');

        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');

        /** @var Event $event */
        $event = $eventModel::find()->andWhere(['id' => $id])->one();
        if ($event) {
            $this->model->event_id = $id;
        }

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                $this->model->generateSlots();
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'event' => $event,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new EventCalendars model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');

        $this->model = $this->eventsModule->createModel('EventCalendars');

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
     * Updates an existing EventCalendars model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);
        $event = $this->model->event;
        $dataProviderSlots = new ActiveDataProvider([
            'query' => $this->model->getEventCalendarsSlots()
        ]);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                if ($dataProviderSlots->count == 0) {
                    $this->model->generateSlots();
                }

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'event' => $event,
            'dataProviderSlots' => $dataProviderSlots,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing EventCalendars model.
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
}
