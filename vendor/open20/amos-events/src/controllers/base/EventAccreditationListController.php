<?php
/**
 * Lombardia Informatica S.p.A.
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
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use Yii;
use yii\helpers\Url;


/**
 * Class EventAccreditationListController.php
 * EventAccreditationListController.php implements the CRUD actions for EventAccreditationList model.
 *
 * @property \open20\amos\events\models\EventAccreditationList $model
 * @property \open20\amos\events\models\search\EventAccreditationListSearch $modelSearch
 *
 * @package open20\amos\events\controllers\base
 */
class EventAccreditationListController extends CrudController
{
    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->eventsModule = AmosEvents::instance();

        $this->setModelObj($this->eventsModule->createModel('EventAccreditationList'));
        $this->setModelSearch($this->eventsModule->createModel('EventAccreditationListSearch'));

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
     * Lists all EventAccreditationList models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex($layout);
    }

    /**
     * Displays a single EventAccreditationList model.
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
     * Creates a new EventAccreditationList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($eid = null)
    {
        $this->setUpLayout('form');
        $this->model = $this->eventsModule->createModel('EventAccreditationList');

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                if (!empty($this->model->event_id)) {
                    return $this->redirect(['/events/event/update', 'id' => $this->model->event_id, '#' => 'w8']);
                } else {
                    return $this->redirect(['update', 'id' => $this->model->id]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
            'eventId' => $eid,
        ]);
    }

    /**
     * Creates a new EventAccreditationList model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = $this->eventsModule->createModel('EventAccreditationList');

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
     * Updates an existing EventAccreditationList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                if (!empty($this->model->event_id)) {
                    return $this->redirect(['/events/event/update', 'id' => $this->model->event_id, '#' => 'w8']);
                } else {
                    return $this->redirect(['update', 'id' => $this->model->id]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing EventAccreditationList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $eventId = null;
            if (!empty($this->model->event_id)) {
                $eventId = $this->model->event_id;
            }

            /** @var EventInvitation $eventInvitationModel */
            $eventInvitationModel = $this->eventsModule->createModel('EventInvitation');

            /** @var EventParticipantCompanion $eventParticipantCompanionModel */
            $eventParticipantCompanionModel = $this->eventsModule->createModel('EventParticipantCompanion');

            $participantsAssociated = $eventInvitationModel::findAll(['accreditation_list_id' => $this->model->id]);
            $companionsAssociated = $eventParticipantCompanionModel::findAll(['event_accreditation_list_id' => $this->model->id]);
            if (count($participantsAssociated) > 0 || count($companionsAssociated) > 0) {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Alcuni partecipanti o accompagnatori hanno la lista di accreditamento che si voleva eliminare associata. Per eliminarla, rimuovere le associazioni presenti.'));
            } else {
                $this->model->delete();
                if (!$this->model->hasErrors()) {
                    Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
                }
            }
            if ($eventId) {
                return $this->redirect(['/events/event/update', 'id' => $eventId, '#' => 'w8']);
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }
}
