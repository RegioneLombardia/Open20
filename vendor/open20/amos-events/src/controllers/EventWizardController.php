<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers
 * @category   CategoryName
 */

namespace open20\amos\events\controllers;

use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\controllers\CrudController;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use open20\amos\events\components\PartsWizardEventCreation;
use open20\amos\events\models\Event;
use open20\amos\events\utility\EventsUtility;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class EventWizardController
 * This is the controller for the event creation wizard.
 *
 * @property \open20\amos\events\models\Event $model
 *
 * @package open20\amos\events\controllers
 */
class EventWizardController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'progress_wizard';

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

        $this->setModelObj($this->eventsModule->createModel('Event'));
        $this->setModelSearch($this->eventsModule->createModel('EventSearch'));

        $this->setAvailableViews([]);

        EventsAsset::register(Yii::$app->view);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'introduction',
                            'description',
                            'organizational-data',
                            'publication',
                            'summary',
                            'finish',
                        ],
                        'roles' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    /**
     * Action for introduction step of the wizard.
     * @param int|null $id
     * @return string|\yii\web\Response
     */
    public function actionIntroduction($id = null)
    {
        Url::remember();

        if (isset($id)) {
            $this->model = $this->findModel($id);
        } else {
            $this->model = $this->eventsModule->createModel('Event');
        }

        $this->detachCwhBehavior();

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post())) {
            $this->model->setScenario(Event::SCENARIO_INTRODUCTION);
            $this->model->begin_date_hour = date("Y-m-d H") . ':00:00';
            if ($this->model->save()) {
                return $this->goToNextPart();
            }
        }

        $this->setParamsForView();
        return $this->render('introduction', [
            'model' => $this->model
        ]);
    }

    /**
     * Action for description step of the wizard.
     * @param int|null $id
     * @return string|\yii\web\Response
     */
    public function actionDescription($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->detachCwhBehavior();
        $this->model->setScenario(Event::SCENARIO_DESCRIPTION);

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();
        return $this->render('description', [
            'model' => $this->model
        ]);
    }

    /**
     * Action for organizational data step of the wizard.
     * @param int|null $id
     * @return string|\yii\web\Response
     */
    public function actionOrganizationalData($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->detachCwhBehavior();
        $this->model->setPublicationScenario();
        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();
        return $this->render('organizationaldata', [
            'model' => $this->model
        ]);
    }

    /**
     * Action for publication step of the wizard.
     * @param int|null $id
     * @return string|\yii\web\Response
     */
    public function actionPublication($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->model->setScenario(Event::SCENARIO_PUBLICATION);

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();
        return $this->render('publication', [
            'model' => $this->model
        ]);
    }

    /**
     * Action for summary step of the wizard.
     * @param int|null $id
     * @return string|\yii\web\Response
     */
    public function actionSummary($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->model->setScenario(Event::SCENARIO_SUMMARY);
        $managerStatus = CommunityUserMm::STATUS_MANAGER_TO_CONFIRM;

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post())) {
            if ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) {
                $managerStatus = CommunityUserMm::STATUS_ACTIVE;
                $this->model->validated_at_least_once = Event::BOOLEAN_FIELDS_VALUE_YES;
                $this->model->visible_in_the_calendar = Event::BOOLEAN_FIELDS_VALUE_YES;
            } else if (($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST) && (in_array($this->model->regola_pubblicazione, [3, 4]))) {
                $managerStatus = CommunityUserMm::STATUS_ACTIVE;
            }

            $communityToCreate = false;
            if (!$this->model->community_id && $this->model->event_management && ($this->model->status != Event::EVENTS_WORKFLOW_STATUS_DRAFT)) {
                EventsUtility::createCommunity($this->model, $managerStatus);
                $communityToCreate = true;
            }
            $this->detachCwhBehavior();
            if ($this->model->save()) {
                $ok = true;
                if ($communityToCreate && $this->model->community_id) {
                    $ok = EventsUtility::duplicateEventLogoForCommunity($this->model);
                }
                if ($ok) {
                    return $this->goToNextPart();
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'There was an error while saving.'));
                }
            }
        }

        $viewPublish = 'hidden';
        $viewPublishRequest = '';
        $loggedUser = Yii::$app->getUser();
        $canDirectlyPublishRoles = [
            'EVENTS_ADMINISTRATOR',
            'EVENTS_VALIDATOR',
            'EVENTS_VALIDATOR_PLATFORM'
        ];
        foreach ($canDirectlyPublishRoles as $canPublishRole) {
            if ($loggedUser->can($canPublishRole)) {
                $viewPublish = '';
                $viewPublishRequest = 'hidden';
            }
        }

        $this->setParamsForView();
        return $this->render('summary', [
            'model' => $this->model,
            'viewPublish' => $viewPublish,
            'viewPublishRequest' => $viewPublishRequest
        ]);
    }

    /**
     * Action for finish step of the wizard.
     * @param int|null $id
     * @return string|\yii\web\Response
     */
    public function actionFinish($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->detachCwhBehavior();
        $finishMessage = AmosEvents::tHtml('amosevents', 'The event');
        $finishMessage .= ' ' . (!is_null($this->model) ? AmosEvents::tHtml('amosevents', 'of type') . " '" . !is_null($this->model->eventType) ? $this->model->eventType->title : '-' . "' " : '');
        $finishMessage .= AmosEvents::tHtml('amosevents', 'has been') . ' ';
        $loggedUser = Yii::$app->getUser();
        $hideWorkflow = isset(\Yii::$app->params['hideWorkflowTransitionWidget']) && \Yii::$app->params['hideWorkflowTransitionWidget'];
        if ((($loggedUser->can('EVENTS_ADMINISTRATOR') || $loggedUser->can('EVENTS_VALIDATOR')) && ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED)) || $hideWorkflow) {
            $finishMessage .= AmosEvents::tHtml('amosevents', 'published');
        } else {
            $finishMessage .= AmosEvents::tHtml('amosevents', 'created and is now waiting to be published');
        }

        $this->setParamsForView();
        return $this->render('finish', [
            'model' => $this->model,
            'finishMessage' => $finishMessage
        ]);
    }

    /**
     * Function to go to next part of the wizard.
     * @return \yii\web\Response
     */
    public function goToNextPart()
    {
        Yii::$app->session->set('previousUrl', Url::previous());
        $partsWizard = new PartsWizardEventCreation([
            'event' => $this->model,
        ]);
        return $this->redirect([
            $partsWizard->getNext(),
            'id' => $this->model->id
        ]);
    }

    /**
     * Set view params for the event creation wizard.
     */
    private function setParamsForView()
    {
        $partsQuestionario = new PartsWizardEventCreation(['event' => $this->model]);
        Yii::$app->view->title = $partsQuestionario->active['index'] . '. ' . $partsQuestionario->active['label'];
        Yii::$app->view->params['model'] = $this->model;
        Yii::$app->view->params['partsQuestionario'] = $partsQuestionario;
        Yii::$app->view->params['hideBreadcrumb'] = true; // This param hide the wizard breadcrumb.
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
        Yii::$app->view->params['textHelp'] = [
            'filename' => 'events-description'
        ];
    }

    /**
     * Used for set page title and breadcrumbs.
     */
    public function setBreadcrumbs()
    {
        Yii::$app->view->params['breadcrumbs'][] = ['label' => AmosEvents::t('amosevents', 'Events'), 'url' => ['/events/event/index']];
        Yii::$app->view->params['breadcrumbs'][] = ['label' => AmosEvents::t('amosevents', 'Event Creation Wizard')];
    }

    /**
     *
     */
    private function detachCwhBehavior()
    {
        /** @var \open20\amos\cwh\AmosCwh $cwhModule */
        $cwhModule = Yii::$app->getModule('cwh');
        if (isset($cwhModule) && in_array($this->eventsModule->model('Event'), $cwhModule->modelsEnabled)) {
            $this->model->detachBehaviorByClassName(\open20\amos\cwh\behaviors\CwhNetworkBehaviors::className());
        }
    }
}
