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

use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\PositionalBreadcrumbHelper;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\utilities\FormUtility;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use open20\amos\events\models\AgidAdministrativePersonsMm;
use open20\amos\events\models\AgidEventDocumentsMm;
use open20\amos\events\models\AgidRelatedEventMm;
use open20\amos\events\models\RelatedEventMm;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;
use open20\amos\events\models\EventInvitationsUpload;
use open20\amos\events\models\EventType;
use open20\amos\events\utility\EventsUtility;
use open20\amos\news\models\base\NewsRelatedEventMm;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Class EventController
 * EventController implements the CRUD actions for Event model.
 *
 * @property \open20\amos\events\models\Event $model
 * @property \open20\amos\events\models\search\EventSearch $modelSearch
 *
 * @package open20\amos\events\controllers\base
 */
class EventController extends CrudController {

    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @var type
     */
    public $moduleCwh;

    /**
     * @var type
     */
    public $scope;

    /**
     * @var \open20\amos\notificationmanager\AmosNotify $moduleNotify
     */
    public $moduleNotify;

    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->initDashboardTrait();

        $this->eventsModule = AmosEvents::instance();

        $this->setModelObj($this->eventsModule->createModel('Event'));
        $this->setModelSearch($this->eventsModule->createModel('EventSearch'));

        EventsAsset::register(Yii::$app->view);

        $this->moduleCwh = Yii::$app->getModule('cwh');
        $this->moduleNotify = Yii::$app->getModule('notify');
        $this->scope = $this->moduleCwh->getCwhScope();

        $this->setAvailableViews([
            'calendar' => [
                'name' => 'calendar',
                // codice HTML per l'intestazione che verrà caricato prima del calendario,
                // per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'intestazione' => '',
                'label' => AmosEvents::t(
                        'amosevents',
                        '{calendarIcon}' . Html::tag('p', AmosEvents::t('amoscore', 'Calendar')), [
                    'calendarIcon' => AmosIcons::show('calendar')
                        ]
                ),
                'url' => '?currentView=calendar'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosEvents::t(
                        'amosevents',
                        '{tableIcon}' . Html::tag('p', AmosEvents::t('amoscore', 'Table')), [
                    'tableIcon' => AmosIcons::show('view-list-alt')
                        ]
                ),
                'url' => '?currentView=grid'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosEvents::t(
                        'amosevents',
                        '{schedaIcon}' . Html::tag('p', AmosEvents::t('amoscore', 'Scheda')), [
                    'schedaIcon' => AmosIcons::show('am am-view-module')
                        ]
                ),
                'url' => '?currentView=icon'
            ],
        ]);

        parent::init();

        if (
                !empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS
        ) {
            $this->view->pluginIcon = 'ic ic-eventi';
        }

        $this->setUpLayout();
    }

    /**
     * Manager status for community create in update action.
     * @param Event $model
     * @param array $oldAttributes
     * @return string
     */
    private function getManagerStatus($model, $oldAttributes) {
        $managerStatus = CommunityUserMm::STATUS_MANAGER_TO_CONFIRM;
        if (
                ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST) && (in_array($this->model->regola_pubblicazione, [3, 4]))
        ) {
            $managerStatus = CommunityUserMm::STATUS_ACTIVE;
        } else if (
                ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) && (($oldAttributes['validated_at_least_once'] == 1) && ($model->validated_at_least_once == 1))
        ) {
            $managerStatus = CommunityUserMm::STATUS_ACTIVE;
        }
        return $managerStatus;
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnParams() {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosEvents::t('amosevents', 'Add new event'),
            'urlCreateNew' => [(array_key_exists("noWizardNewLayout", Yii::$app->params) ? '/events/event/create' : '/events/event-wizard/introduction')
            ],
            'otherOptions' => [
                'title' => AmosEvents::t('amosevents', 'Add new event'),
                'class' => 'btn btn-primary'
            ]
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     * @param bool $setCurrentDashboard
     */
    protected function setListViewsParams($setCurrentDashboard = true) {
        $this->setCreateNewBtnParams();
        $this->setUpLayout('list');
        if ($setCurrentDashboard) {
            $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        }
        Yii::$app->session->set(AmosEvents::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosEvents::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle News page title (ie. Created by news, ...)
     */
    public function setTitleAndBreadcrumbs($pageTitle) {
        Yii::$app->session->set('previousTitle', $pageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }

    /**
     * This method calculate the template of the grid view action columns
     * @param string|null $addActionColumns
     * @return string
     */
    public function getGridViewActionColumnsTemplate($addActionColumns = null) {
        $actionColumnDefault = ($this->eventsModule->enableContentDuplication ? '{duplicateBtn}' : '')
                . '{view}{update}{delete}';
        $actionColumnToPublish = '{publish}{reject}';
        $actionColumnManager = '{community}';
        $actionColumn = $actionColumnDefault;
        if (isset($addActionColumns)) {
            switch ($addActionColumns) {
                case 'toPublish' :
                    $actionColumn = $actionColumnToPublish . $actionColumn;
                    break;
                case 'management' :
                    $actionColumn = $actionColumnManager;
                    break;
            }
        }
        return $actionColumn;
    }

    /**
     * Base operations for list views
     * @param string $pageTitle
     * @param bool $setCurrentDashboard
     * @return string
     */
    protected function baseListsAction($pageTitle, $setCurrentDashboard = true, $addActionColumns = '') {
        Url::remember();
        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams($setCurrentDashboard);
        $renderParams = [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ];
        if (strlen($addActionColumns) > 0) {
            $renderParams['addActionColumns'] = $addActionColumns;
        }
        return $this->render('index', $renderParams);
    }

    /**
     * Lists all Event models.
     * @param string|null $layout
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = NULL) {
        if ($this->eventsModule->openEventsToGuests) {
            return $this->redirect(['/events/event/all-events']);
        }
        return $this->redirect(['/events/event/own-interest']);
    }

    /**
     * Get latitude and longitude of a place.
     * @param string $position
     * @return array
     * @deprecated
     */
    public function getMapPosition($position) {
        if (empty($position)) {
            $position = $this->eventsModule->geoLocationDefaultPosition;
        }

        /**
         * TODO INSERT KEY GOOGLE ON PARAMS PROJECT
         */
        if (!is_null(Yii::$app->params['googleMapsApiKey'])) {
            $googleMapsKey = Yii::$app->params['googleMapsApiKey'];
        } elseif (Yii::$app->params['google_places_api_key']) {
            $googleMapsKey = Yii::$app->params['google_places_api_key'];
        } elseif (!is_null(Yii::$app->params['google-maps']) && !is_null(Yii::$app->params['google-maps']['key'])) {
            $googleMapsKey = Yii::$app->params['google-maps']['key'];
        } else {
            Yii::$app->session->addFlash(
                    'warning',
                    BaseAmosModule::t(
                            'amoscore',
                            'Errore di comunicazione con google: impossibile trovare la posizione nella mappa.'
                    )
            );
            return [];
        }

        $GeoCoderParams = urlencode($position);
        $UrlGeocoder = "https://maps.googleapis.com/maps/api/geocode/json?address=$GeoCoderParams&key=$googleMapsKey";
        $origin = [];
        try {
            $ResulGeocoding = Json::decode(file_get_contents($UrlGeocoder));
        } catch (\Exception $exception) {
            return $origin;
        }

        if ($ResulGeocoding['status'] == 'OK') {
            if (isset($ResulGeocoding['results']) && isset($ResulGeocoding['results'][0])) {
                $Indirizzo = $ResulGeocoding['results'][0];

                if (isset($Indirizzo['geometry'])) {
                    $Location = $Indirizzo['geometry']['location'];

                    if (isset($Location['lat'])) {
                        $origin['latitudine'] = $Location['lat'];
                    }
                    if (isset($Location['lng'])) {
                        $origin['longitudine'] = $Location['lng'];
                    }
                }
            }
        }

        return $origin;
    }

    /**
     * Displays a single Event model.
     * @param integer $id
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionView($id) {
        Url::remember();

        if (
                !empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS
        ) {
            $this->setUpLayout('main_events');
        } else {
            $this->setUpLayout('main');
        }

        /** @var Event $model */
        $model = $this->findModel($id);

        if (!empty($this->module->useFrontendView) && $this->module->useFrontendView == true) {
            return $this->redirect($model->getFullViewUrl());
        }

        $query = new Query();
        $query->select("sector, count([[seat]]) as 'seats', event_id, id, SUM(
            CASE
            WHEN (status = 1 OR status = 3)
            THEN 1
            ELSE 0
            END) as 'empty_seats'"
                )
                ->from('event_seats')
                ->andWhere(['event_id' => $id])
                ->andWhere(['event_seats.deleted_at' => null])
                ->groupBy('sector');

        $events = [];
        $dataProviderEvents = null;
        $enableRelatedEvents = $this->eventsModule->enableRelatedEvents;
        if ($enableRelatedEvents) {
            $correlatedEvent = RelatedEventMm::find()->andWhere(['main_event_id' => $id])->select('related_event_id')->asArray()->all();
            foreach ($correlatedEvent as $ids) {
                $events[] = $ids['related_event_id'];
            }

            $amosEventInstance = AmosEvents::instance();
            $modelEvent = $amosEventInstance->createModel('Event');
            $events = $modelEvent::find()
                    ->andWhere(['in', 'id', $events])
                    ->andWhere(['status' => 'EventWorkflow/PUBLISHED']);
            $dataProviderEvents = new ActiveDataProvider([
                'query' => $events
            ]);
        }

        $dataProviderSeats = null;
        if ($model->seats_management) {
            $dataProviderSeats = new ArrayDataProvider([
                'allModels' => $query->all()
            ]);
        }

        $dataProviderSlots = null;
        if ($model->slots_calendar_management) {
            $dataProviderSlots = new ActiveDataProvider([
                'query' => $this->model->getEventCalendars()->orderBy('group')
            ]);
        }

        $resetScope = Yii::$app->request->get('resetscope');
        if (!is_null($resetScope) && ($resetScope == 1)) {
            $cwhModule = \Yii::$app->getModule('cwh');
            if (isset($cwhModule)) {
                /** @var \open20\amos\cwh\AmosCwh $cwhModule */
                $cwhModule->resetCwhScopeInSession();
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        $latLngOriginStr = $model->event_address_house_number ? $model->event_address_house_number . ' ' : '';
        $latLngOriginStr .= ($model->event_address ? $model->event_address . ', ' : '');
        $latLngOriginStr .= (!is_null($model->cityLocation) ? $model->cityLocation->nome . ', ' : '');
        $latLngOriginStr .= (!is_null($model->countryLocation) ? $model->countryLocation->nome : '');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render(
                        ($this->eventsModule->enableAgid ? 'viewAgid' : 'view'),
                        [
                            'currentView' => $this->getCurrentView(),
                            'model' => $model,
                            'position' => $latLngOriginStr,
                            'dataProviderEvents' => $dataProviderEvents,
                            'dataProviderSeats' => $dataProviderSeats,
                            'dataProviderSlots' => $dataProviderSlots
                        ]
        );
    }

    /**
     * Override this method to make operations after the creation
     * of a new Event model in the create and create-ajax actions.
     * @param Event $model
     * @return Event
     */
    protected function afterCreateNewEventModel($model) {
        return $model;
    }

    /**
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->setUpLayout('form');

        if ($this->eventsModule->hidePubblicationDate == true) {
            /** @var Event $model */
            $this->model = $this->eventsModule->createModel('Event', [
                'scenario' => Event::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE
            ]);
            $this->model->setScenario(Event::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE);
        } else {
            /** @var Event $model */
            $this->model = $this->eventsModule->createModel('Event', ['scenario' => Event::SCENARIO_CREATE]);
            $this->model->setScenario(Event::SCENARIO_CREATE);
        }

        $this->model = $this->afterCreateNewEventModel($this->model);
        $this->model->detachBehavior('seoBehavior');

        /** @var EventType $eventTypeModel */
        $eventTypeModel = $this->eventsModule->createModel('EventType');
        /** @var ActiveQuery $queryTypes */
        $queryTypes = $eventTypeModel::find();
        $eventTypes = $queryTypes->andWhere(['enabled' => $eventTypeModel::ENABLED])->all();
        $countEventTypes = count($eventTypes);
        if ($countEventTypes == 0) {
            Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', '#no_event_types_enabled'));
            return $this->redirect(Url::previous());
        } elseif ($countEventTypes == 1) {
            /** @var EventType $eventType */
            $eventType = reset($eventTypes);
            $this->model->event_type_id = $eventType->id;
        }

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $validateOnSave = true;
            if ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST) {
                $this->model->status = Event::EVENTS_WORKFLOW_STATUS_DRAFT;
                $this->model->save();
                $this->model->status = Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST;
                $validateOnSave = false;
            }
            if ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) {
                $this->model->status = Event::EVENTS_WORKFLOW_STATUS_DRAFT;
                $this->model->save();
                $this->model->status = Event::EVENTS_WORKFLOW_STATUS_PUBLISHED;
                $validateOnSave = false;
            }
            if ($this->model->save($validateOnSave)) {
                /** @var EventAccreditationList $accreditamentoList */
                $accreditamentoList = $this->eventsModule->createModel('EventAccreditationList');
                $accreditamentoList->event_id = $this->model->id;
                $accreditamentoList->position = 1;
                $accreditamentoList->title = "Generica";
                $accreditamentoList->save();
                Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element successfully created.'));
                return $this->redirect(['/events/event/update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not created, check the data entered.'));
            }
        }

        return $this->render(
                        'create',
                        [
                            'model' => $this->model,
                            'countEventTypes' => $countEventTypes,
                            'enableAgid' => $this->eventsModule->enableAgid,
                            'fid' => NULL,
                            'dataField' => NULL,
                            'dataEntity' => NULL,
                            'moduleCwh' => $this->moduleCwh,
                            'scope' => $this->scope
                        ]
        );
    }

    /**
     * Creates a new Event model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField) {
        $this->setUpLayout('form');

        /** @var Event $model */
        $model = $this->eventsModule->createModel('Event', ['scenario' => Event::SCENARIO_CREATE]);

        $model = $this->afterCreateNewEventModel($model);

        if (\Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return json_encode($model->toArray());
            }
        }

        return $this->renderAjax('_formAjax', [
                    'model' => $model,
                    'fid' => $fid,
                    'dataField' => $dataField,
                    'moduleCwh' => $this->moduleCwh,
                    'scope' => $this->scope
        ]);
    }

    /**
     * Updates an existing Event model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $backToEditStatus = false) {
        Url::remember();
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        $this->updateFindModel();

        $enableAgid = $this->eventsModule->enableAgid;
        $siteManagementModule = null;
        $imageSlider = null;
        $dataProviderImageSlider = null;
        $videoSlider = null;
        $dataProviderVideoSlider = null;

        if ($enableAgid) {
            $this->model->agidRelatedEventsMm = $this->model->agidRelatedEvents;
            $this->model->agidAdministrativePersonsMm = $this->model->agidAdministrativePersons;
            $this->model->agidEventDocumentsMm = $this->model->agidEventDocuments;
            /** @var \amos\sitemanagement\Module|AmosModule|CmsModuleInterface $siteManagementModule */
            $siteManagementModule = \Yii::$app->getModule('sitemanagement');
            if (!is_null($siteManagementModule)) {

                // Get or create image slider.
                $imageSlider = $this->model->sliderImage;
                if (is_null($imageSlider)) {
                    $imageSlider = new \amos\sitemanagement\models\SiteManagementSlider();
                    $imageSlider->section_id = null;
                    $imageSlider->title = $this->model->getTitleSlider();
                    $imageSlider->save(false);
                    $this->model->agid_image_slider_id = $imageSlider->id;
                    $this->model->save(false);
                }
                $dataProviderImageSlider = new ActiveDataProvider(['query' => $imageSlider->getSliderElems()->orderBy(['order' => SORT_ASC])]);

                // Get or create video slider.
                $videoSlider = $this->model->sliderVideo;
                if (is_null($videoSlider)) {
                    $videoSlider = new \amos\sitemanagement\models\SiteManagementSlider();
                    $videoSlider->section_id = null;
                    $videoSlider->title = $this->model->getTitleSlider();
                    $videoSlider->save(false);
                    $this->model->agid_video_slider_id = $videoSlider->id;
                    $this->model->save(false);
                }
                $dataProviderVideoSlider = new ActiveDataProvider(['query' => $videoSlider->getSliderElems()->orderBy(['order' => SORT_ASC])]);
            }
        }

        $oldAttributes = $this->model->getOldAttributes();

        if (Yii::$app->request->post()) {
            if ($this->model->load(Yii::$app->request->post())) {
                if ($this->model->seats_management) {
                    if (is_null($this->model->seats_available)) {
                        $this->model->seats_available = 0;
                    } else {
                        $this->model->seats_available = $this->model->getEventSeats()->count();
                    }
                }

                if ($this->model->validate()) {

                    $validateOnSave = true;
                    if (($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) && ($this->model->validated_at_least_once != Event::BOOLEAN_FIELDS_VALUE_YES)) {
                        $this->model->status = Event::EVENTS_WORKFLOW_STATUS_DRAFT;
                        $this->model->save();
                        $this->model->status = Event::EVENTS_WORKFLOW_STATUS_PUBLISHED;
                        $validateOnSave = false;
                        $this->model->validated_at_least_once = Event::BOOLEAN_FIELDS_VALUE_YES;
                        $this->model->visible_in_the_calendar = Event::BOOLEAN_FIELDS_VALUE_YES;
                    }

                    if ($this->model->status != Event::EVENTS_WORKFLOW_STATUS_DRAFT) {
                        // if ($this->model->event_management) {
                        if (is_null($this->model->community_id)) {
                            $managerStatus = CommunityUserMm::STATUS_ACTIVE; //$this->getManagerStatus($this->model, $oldAttributes);
                            $ok = EventsUtility::createCommunity($this->model, $managerStatus);
                        } else {
                            $ok = EventsUtility::updateCommunity($this->model);
                        }
                        if ($ok && ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED)) {
                            if (($oldAttributes['validated_at_least_once'] == 0) && ($this->model->validated_at_least_once == 1)) {
                                // If it's the first validation, check if the logged user is the same as the manager.
                                // In that case set the manager in the active status.
                                $eventManagers = EventsUtility::findEventManagers($this->model);
                                foreach ($eventManagers as $eventManager) {
                                    /** @var CommunityUserMm $eventManager */
                                    if (($eventManager->user_id == Yii::$app->getUser()->getId()) && ($eventManager->status != CommunityUserMm::STATUS_ACTIVE)) {
                                        $eventManager->status = CommunityUserMm::STATUS_ACTIVE;
                                        $eventManager->save();
                                    }
                                }
                            }

                            $ok = EventsUtility::checkOneConfirmedManagerPresence($this->model);
                            if (!$ok) {
                                Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'The event can not be published. There must be at least one confirmed manager.'));
                            }
                        }
//                        }
                        if ($this->eventsModule->enableAutoInviteUsers && ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) && ($this->model->eventType->event_type != EventType::TYPE_INFORMATIVE)) {
                            $this->doAddUsersInvitations($this->model);
                            $invitationsData = $this->model->getInvitationsData(true);
                            $this->doSendInvitations($this->model->id, $invitationsData, true);
                        }
                    }
                    if (!empty($this->model->dirtyAttributes)) {
                        $ok = $this->model->save($validateOnSave);
                    }
                    if ($ok) {

                        $okCommunity = true;
                        if (!is_null($this->model->community_id)) {
                            $okCommunity = EventsUtility::duplicateEventLogoForCommunity($this->model);
                        }

                        if ($enableAgid) {
                            // Save related events
                            $okRelatedEvents = $this->saveAgidRelatedEvents();

                            // Save administrative persons
                            $okAdministrativePersons = $this->saveAdministrativePersons();

                            // Save event documents
                            $okEventDocuments = $this->saveEventDocuments();

                            // Check all external model save.
                            $ok = $okCommunity && $okRelatedEvents && $okAdministrativePersons && $okEventDocuments;
                        } else {
                            $ok = $okCommunity;
                        }

                        if ($ok) {
                            Yii::$app->getSession()->addFlash('success', AmosEvents::t('amosevents', 'Element successfully updated.'));
                        } else {
                            Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'There was an error while saving.'));
                        }
                        if ($this->model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST && !\Yii::$app->user->can('EventValidate', ['model' => $this->model])) {
                            return $this->redirect(['/events/event/created-by']);
                        }
                        return $this->redirect(['/events/event/update', 'id' => $this->model->id]);
                    } else {
                        Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'There was an error while saving.'));
                        return $this->render('create', [
                                    'model' => $this->model,
                                    'enableAgid' => $this->eventsModule->enableAgid,
                                    'fid' => NULL,
                                    'dataField' => NULL,
                                    'dataEntity' => NULL,
                                    'moduleCwh' => $this->moduleCwh,
                                    'scope' => $this->scope
                        ]);
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Element not updated, check the data entered.'));
                }
            }
        } else {
            if ($backToEditStatus && ($this->model->status != $this->model->getDraftStatus() && !Yii::$app->user->can('EventValidate', ['model' => $this->model]))) {
                $this->model->status = $this->model->getDraftStatus();
                $ok = $this->model->save(false);
                if (!$ok) {
                    Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'There was an error while saving.'));
                }
            }
        }

        /** @var EventInvitationsUpload $eventInvitationUploadModel */
        $eventInvitationUploadModel = $this->eventsModule->createModel('EventInvitationsUpload');

        return $this->render('update', [
                    'model' => $this->model,
                    'upload' => $eventInvitationUploadModel,
                    'enableAgid' => $enableAgid,
                    'siteManagementModule' => $siteManagementModule,
                    'imageSlider' => $imageSlider,
                    'dataProviderImageSlider' => $dataProviderImageSlider,
                    'videoSlider' => $videoSlider,
                    'dataProviderVideoSlider' => $dataProviderVideoSlider,
                    'fid' => NULL,
                    'dataField' => NULL,
                    'dataEntity' => NULL,
                    'moduleCwh' => $this->moduleCwh,
                    'scope' => $this->scope
        ]);
    }

    /**
     * Method called after findModel in action update.
     */
    protected function updateFindModel() {
        
    }

    /**
     * Save all related events selected by user.
     * @return bool
     */
    protected function saveAgidRelatedEvents() {
        $post = \Yii::$app->request->post($this->model->formName());
        $attrPost = $post['agidRelatedEventsMm'];
        $relatedEventsMms = $this->model->agidRelatedEventMms;
        foreach ($relatedEventsMms as $relatedEventsMm) {
            /** @var AgidRelatedEventMm $relatedEventsMm */
            $relatedEventsMm->delete();
        }
        $ok = FormUtility::saveMmsFields(
                        $attrPost,
                        AgidRelatedEventMm::class,
                        'main_event_id',
                        $this->model->id,
                        'related_event_id'
        );
        if (!$ok) {
            Yii::$app->getSession()->addFlash('danger', AmosEvents::t('amosevents', 'Error while linking Event to Event Related'));
        }
        return $ok;
    }

    /**
     * Save all administrative persons selected by user.
     * @return bool
     */
    protected function saveAdministrativePersons() {
        $post = \Yii::$app->request->post($this->model->formName());
        $attrPost = $post['agidAdministrativePersonsMm'];
        $agidAdministrativePersonsMms = $this->model->agidAdministrativePersonsMms;
        foreach ($agidAdministrativePersonsMms as $agidAdministrativePersonsMm) {
            /** @var AgidAdministrativePersonsMm $agidAdministrativePersonsMm */
            $agidAdministrativePersonsMm->delete();
        }
        $ok = FormUtility::saveMmsFields(
                        $attrPost,
                        AgidAdministrativePersonsMm::class,
                        'event_id',
                        $this->model->id,
                        'person_id'
        );
        if (!$ok) {
            Yii::$app->getSession()->addFlash(
                    'danger',
                    AmosEvents::t(
                            'amosevents',
                            'Error while linking Event to administrative persons'
                    )
            );
        }
        return $ok;
    }

    /**
     * Save all event documents selected by user.
     * @return bool
     */
    protected function saveEventDocuments() {
        $post = \Yii::$app->request->post($this->model->formName());
        $attrPost = $post['agidEventDocumentsMm'];
        $agidEventDocumentsMms = $this->model->agidEventDocumentsMms;
        foreach ($agidEventDocumentsMms as $agidEventDocumentsMm) {
            /** @var AgidEventDocumentsMm $agidEventDocumentsMm */
            $agidEventDocumentsMm->delete();
        }
        $ok = FormUtility::saveMmsFields(
                        $attrPost,
                        AgidEventDocumentsMm::class,
                        'event_id',
                        $this->model->id,
                        'document_id'
        );
        if (!$ok) {
            Yii::$app->getSession()->addFlash(
                    'danger',
                    AmosEvents::t(
                            'amosevents',
                            'Error while linking Event to documents'
                    )
            );
        }
        return $ok;
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        /** @var Event $model */
        $model = $this->findModel($id);
        if ($model) {
            $ok = true;
            if (!is_null($model->community) && is_null($model->community->deleted_at)) {
                try {
                    /**
                     * TODO Devo cancellare qui i partecipanti alla community
                     * perché la loro cancellazione implica una notifica via mail
                     * e dato che la community di evento è nascosta e nessuno
                     * dovrebbe sapere che esiste, nessuno deve riceve tale
                     * notifica in quanto non sa di farne parte in realtà.
                     * Sarebbe un bug da risolvere in community.
                     */
                    $model->community->delete();
                } catch (\Exception $exception) {
                    $ok = false;
                    Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosEvents::t(
                                    'amosevents',
                                    'Errors while deleting event community.'
                            )
                    );
                }
                if ($model->community->getErrors()) {
                    $ok = false;
                    Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosEvents::t(
                                    'amosevents',
                                    'Errors while deleting event community.'
                            )
                    );
                }
            }

            if ($ok) {
                $model->delete();
                if (!$model->getErrors()) {
                    Yii::$app->getSession()->addFlash(
                            'success',
                            AmosEvents::t('amosevents', 'Element succesfully deleted.')
                    );
                } else {
                    Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosEvents::t('amosevents', 'Errors while deleting element.')
                    );
                }
            }
        } else {
            Yii::$app->getSession()->addFlash(
                    'danger', AmosEvents::t('amosevents', 'Element not found.')
            );
        }
        return $this->redirect(Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey()));
    }
}
