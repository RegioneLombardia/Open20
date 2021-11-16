<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 27/02/2020
 * Time: 12:10
 */

namespace open20\amos\events\controllers;


use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\ActiveForm;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\events\components\EventWizardParts;
use open20\amos\events\models\CmsLanding;
use open20\amos\events\models\CmsLandingModel;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventEmailTemplates;
use open20\amos\events\models\EventLanding;
use open20\amos\events\models\EventLocation;
use open20\amos\events\models\EventLocationEntrances;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\models\search\UserEventSearch;
use open20\amos\events\utility\EventsUtility;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use \Yii;
use yii\base\Response;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class WizardController extends CrudController
{


    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;
    public $scope = null;

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
                            'base-info',
                            'where-and-when',
                            'image',
                            'landing',
                            'emails',
                            'community',
                            'invite',
                            'finish',
                            'get-values',
                            'render-search-ajax',
                            'search-users',
                            'save-list',
                            'ajax-event-tag-list',
                            'send-email-test',
                            'not-associated-forbidden'
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
     * @inheritdoc
     */
    public function init()
    {
        $this->eventsModule = AmosEvents::instance();

        $this->setModelObj($this->eventsModule->createModel('Event'));
        $this->setModelSearch($this->eventsModule->createModel('EventSearch'));

        $this->setAvailableViews([]);

        // EventsAsset::register(\Yii::$app->view);

        parent::init();
        \Yii::$app->params['bsVersion'] = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container';
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
    }

    /**
     * Set view params for the event creation wizard.
     */
    private function setParamsForView()
    {
        $parts = new EventWizardParts(['model' => $this->model]);
        Yii::$app->view->title = $parts->active['index'] . '. ' . $parts->active['label'];
        Yii::$app->view->params['model'] = $this->model;
        Yii::$app->view->params['partsQuestionario'] = $parts;
        Yii::$app->view->params['hidePartsLabel'] = true; // This param hide the second title under the wizard progress bar.
        Yii::$app->view->params['disablePlatformLinks'] = true;
        Yii::$app->view->params['hideBreadcrumb'] = true; // This param hide the breadcrumb in the wizard layout.
        Yii::$app->view->params['hidePartsUrl'] = true; // This param disable the progress wizard menu links.
    }

    /**
     * @return \yii\web\Response
     */
    public function goToNextPart()
    {
        $parts = new EventWizardParts(['model' => $this->model]);

        return $this->redirect([$parts->getNext(), 'id' => $this->model->id]);
    }

    /**
     * @param null $id
     * @return string|void
     */
    public function actionIndex($id = null)
    {
        $this->redirect(['base-info', 'id' => $id]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionBaseInfo($id = null)
    {
        $currentDg = EventsUtility::getCurrentDg();
        if (empty($currentDg)) {
            return $this->redirect('not-associated-forbidden');
        }

        if (isset($id)) {
            $this->model = $this->findModel($id);
            $this->model->loadTagPreferences();
            $this->model->loadCustomTags();
        } else {
            $this->model = $this->eventsModule->createModel('Event');
            $group = EventsUtility::getCurrentDg();
            if ($group) {
                $this->model->event_group_referent_id = $group->id;
            }
        }

        $this->model->loadTagPreferences();
        $this->model->loadCustomTags();
        $preferenceCenterTags = EventsUtility::getPreferenceCenterTags();


        $locations = EventLocation::find()->andWhere(['OR',
            ['hidden' => false],
            ['hidden' => true, 'id' => $this->model->event_location_id]
        ])->all();
        $locationsForRadio = [];
        foreach ($locations as $location) {
            $locationsForRadio[$location->id] = $location->name;
        }
        $locationsForRadio['other'] = AmosEvents::t('amosevents', 'Other');
        $eventLocation = new EventLocation();
        $eventLocationEntrance = new EventLocationEntrances();

        if (empty($this->model->begin_date_hour)) {
            $this->model->beginDate = date("Y-m-d");
            $this->model->beginHour = date("H:i:s");
        } else {
            $beginDate = new \DateTime($this->model->begin_date_hour);
            $this->model->beginDate = $beginDate->format("Y-m-d");
            $this->model->beginHour = $beginDate->format("H:i:s");
        }

        if (empty($this->model->end_date_hour)) {
            $this->model->endDate = date("Y-m-d");
            $this->model->endHour = date("H:i:s", strtotime('3 hour'));
        } else {
            $endDate = new \DateTime($this->model->end_date_hour);
            $this->model->endDate = $endDate->format("Y-m-d");
            $this->model->endHour = $endDate->format("H:i:s");
        }

        $eventLanding = $this->model->eventLanding;
        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $templates = $utility->getCmsTemplates();
        $arrayTemplates = [];
        $htmlTemplate = [];
        foreach ($templates as $template) {
            if ($template['title'] != 'Neutro' || \Yii::$app->user->can('SUPER_USER_EVENT')) {
                $arrayTemplates [$template['id']] = $template['title'];
                $htmlTemplate[$template['id']] = EventsUtility::getClassPreviewLanding($template['title']);
            }
        }
        if (empty($eventLanding)) {
            $eventLanding = new EventLanding();
            $eventLanding->event_id = $this->model->id;
            //default
            $eventLanding->social_reg = true;
            $eventLanding->facebook_reg = true;
            $eventLanding->twitter_reg = true;
            $eventLanding->linkedin_reg = true;
            $eventLanding->goolge_reg = true;
            $eventLanding->spid_cns_reg = true;
            $eventLanding->user_name_reg = true;
        }

//        $this->setUpLayout('form');
        $post = \Yii::$app->request->post();
        if ($post && $this->model->load($post) && $eventLanding->load($post) && $eventLanding->validate()) {
            if(empty($this->model->event_id)){
                $this->model->event_id = null;
            }
            $this->model->event_management = false;
            $this->model->publish_in_the_calendar = true;
            $this->model->abilita_codice_fiscale_in_form = '0';
            $this->model->numero_max_accompagnatori = '0';

            if (!empty($this->model->beginDate)) {
                $this->model->begin_date_hour = $this->model->beginDate . ' ' . $this->model->beginHour;
            }
            if (!empty($this->model->endDate)) {
                $this->model->end_date_hour = $this->model->endDate . ' ' . $this->model->endHour;
            }


            if ($this->model->validate()) {
                $isLocationValid = true;
                if ($this->model->event_location_id == 'other') {
                    $isLocationValid = false;
                    $eventLocation->load(\Yii::$app->request->post());
                    $eventLocationEntrance->load(\Yii::$app->request->post());
                    if ($eventLocation->validate() && $eventLocationEntrance->validate()) {
                        $isLocationValid = true;
                        $eventLocation->hidden = true;
                        $eventLocation->save(false);
                        $eventLocationEntrance->event_location_id = $eventLocation->id;
                        $eventLocationEntrance->save(false);
                        $this->model->event_location_id = $eventLocation->id;
                        $this->model->event_location_entrance_id = $eventLocationEntrance->id;
                    }
                }

                if ($isLocationValid && $this->model->save()) {
                    $this->createUpdateCommunity($this->model);

                    if (empty($eventLanding->url)) {
                        $eventLanding->url = $this->model->getPrettyUrl();
                    }
                    if (!$eventLanding->validate('url')) {
                        $eventLanding->url = $eventLanding->url . '-' . $this->model->id;
                    }

                    $eventLanding->event_id = $this->model->id;
                    $eventLanding->save(false);

                    $this->model->createDefaultAccreditationList();
                    $this->model->savePreferencesTags();
                    $this->model->saveCustomTags();

                    return $this->goToNextPart();
                }
            }
        }


        $this->setParamsForView();

        return $this->render('base-info', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'preferenceCenterTags' => $preferenceCenterTags,
            'locations' => $locations,
            'locationsForRadio' => $locationsForRadio,
            'eventLocation' => $eventLocation,
            'eventLocationEntrance' => $eventLocationEntrance,
            'arrayTemplates' => $arrayTemplates,
            'eventLanding' => $eventLanding,
            'htmlTemplate' => $htmlTemplate

        ]);
    }

    /**
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBaseInfoOld($id = null)
    {
        if (isset($id)) {
            $this->model = $this->findModel($id);
            $this->model->loadTagPreferences();
            $this->model->loadCustomTags();
        } else {
            $this->model = $this->eventsModule->createModel('Event');
            $group = EventsUtility::getCurrentDg();
            if ($group) {
                $this->model->event_group_referent_id = $group->id;
            }
        }
        $this->model->setScenario(Event::SCENARIO_WIZARD_BASEINFO);
        $preferenceCenterTags = EventsUtility::getPreferenceCenterTags();

//        $this->setUpLayout('form');

        if (Yii::$app->request->post() && $this->model->load(Yii::$app->request->post())) {
            $this->model->publish_in_the_calendar = true;
            $this->model->begin_date_hour = date("Y-m-d H:i:s");
            $this->model->event_management = false;
            if ($this->model->validate()) {
                if ($this->model->save()) {
                    $this->model->savePreferencesTags();
                    $this->model->saveCustomTags();
                    $this->model->createDefaultAccreditationList();
                    return $this->goToNextPart();
                }
            }
        }

        $this->setParamsForView();
        return $this->render('base-info', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'preferenceCenterTags' => $preferenceCenterTags
        ]);
    }

    /**
     * @param null $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionWhereAndWhen($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->model->setScenario(Event::SCENARIO_WIZARD_WHERE_AND_WHEN);
        $this->model->detachBehavior('seoBehavior');

        $locations = EventLocation::find()->andWhere(['OR',
            ['hidden' => false],
            ['hidden' => true, 'id' => $this->model->event_location_id]
        ])->all();

        $locationsForRadio = [];
        foreach ($locations as $location) {
            $locationsForRadio[$location->id] = $location->name;
        }
        $locationsForRadio['other'] = AmosEvents::t('amosevents', 'Other');
        $eventLocation = new EventLocation();
        $eventLocationEntrance = new EventLocationEntrances();

        if (empty($this->model->begin_date_hour)) {
            $this->model->beginDate = date("Y-m-d");
            $this->model->beginHour = date("H:i:s");
        } else {
            $beginDate = new \DateTime($this->model->begin_date_hour);
            $this->model->beginDate = $beginDate->format("Y-m-d");
            $this->model->beginHour = $beginDate->format("H:i:s");
        }

        if (empty($this->model->end_date_hour)) {
            $this->model->endDate = date("Y-m-d");
            $this->model->endHour = date("H:i:s", strtotime('3 hour'));
        } else {
            $endDate = new \DateTime($this->model->end_date_hour);
            $this->model->endDate = $endDate->format("Y-m-d");
            $this->model->endHour = $endDate->format("H:i:s");
        }


        $this->detachCwhBehavior();
//        $this->model->setScenario(Event::SCENARIO_DESCRIPTION);

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post())) {
            if (!empty($this->model->beginDate)) {
                $this->model->begin_date_hour = $this->model->beginDate . ' ' . $this->model->beginHour;
            }
            if (!empty($this->model->endDate)) {
                $this->model->end_date_hour = $this->model->endDate . ' ' . $this->model->endHour;
            }

            if ($this->model->event_location_id == 'other') {
                $eventLocation->load(\Yii::$app->request->post());
                $eventLocationEntrance->load(\Yii::$app->request->post());
                $eventLocation->hidden = true;
                $eventLocation->save(false);
                $eventLocationEntrance->event_location_id = $eventLocation->id;
                $eventLocationEntrance->save(false);
                $this->model->event_location_id = $eventLocation->id;
                $this->model->event_location_entrance_id = $eventLocationEntrance->id;
            }

            if ($this->model->save()) {
                return $this->goToNextPart();
            }
        }

        $this->setParamsForView();
        return $this->render('where-and-when', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'locations' => $locations,
            'locationsForRadio' => $locationsForRadio,
            'eventLocation' => $eventLocation,
            'eventLocationEntrance' => $eventLocationEntrance,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImage($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->detachCwhBehavior();
        $this->model->detachBehavior('seoBehavior');
        $this->model->setScenario(Event::SCENARIO_WIZARD_IMAGE);

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();
        return $this->render('image', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule

        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionLanding($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $eventLanding = $this->model->eventLanding;
        $this->detachCwhBehavior();

        $this->model->setScenario(Event::SCENARIO_WIZARD_LANDING);

        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $templates = $utility->getCmsTemplates();
        $arrayTemplates = [];
        $htmlTemplate = [];

        foreach ($templates as $template) {
            $arrayTemplates [$template['id']] = $template['title'];
            $htmlTemplate[$template['id']] = EventsUtility::getClassPreviewLanding($template['title']);

        }

        if (empty($eventLanding)) {
            $eventLanding = new EventLanding();
            $eventLanding->url = $this->model->getPrettyUrl();
            $eventLanding->event_id;

            //default
            $eventLanding->social_reg = true;
            $eventLanding->facebook_reg = true;
            $eventLanding->twitter_reg = true;
            $eventLanding->linkedin_reg = true;
            $eventLanding->spid_cns_reg = true;
            $eventLanding->user_name_reg = true;
        }

        $post = Yii::$app->getRequest()->post();
        if ($post && $this->model->load($post) && $eventLanding->load($post) && $eventLanding->validate()) {
            $this->model->detachBehavior('seoBehavior');

            $eventLanding->event_id = $id;
            $eventLanding->save(false);
            if ($this->model->save()) {
                //$isOk = $eventLanding->createLandingPage($this->model);
                return $this->goToNextPart();
            }
        }

//        if ( $post && $this->model->load($post) && $modelLanding->load($post) ) {
//            $isOk = $modelLanding->createLandingPage($this->model);
//            if($this->model->save()) {
//                return $this->goToNextPart();
//            }
//        }

        $this->setParamsForView();
        return $this->render('landing', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'arrayTemplates' => $arrayTemplates,
            'eventLanding' => $eventLanding,
            'htmlTemplate' => $htmlTemplate
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCommunity($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->model->setScenario(Event::SCENARIO_WIZARD_COMMUNITY);

        $this->detachCwhBehavior();
        $this->model->detachBehavior('seoBehavior');

        $isCreate = false;
        if (is_null($this->model->community_id)) {
            $managerStatus = CommunityUserMm::STATUS_ACTIVE;//$this->getManagerStatus($model, $oldAttributes);
            $ok = EventsUtility::createCommunity($this->model, $managerStatus, CommunityType::COMMUNITY_TYPE_CLOSED);
            $isCreate = true;
        } else {
            $ok = EventsUtility::updateCommunity($this->model);
        }

        $community = Community::find()->andWhere(['id' => $this->model->community_id])->one();
        if ($isCreate) {
            $community->force_workflow = 0;
        } else {
            $community->force_workflow = $community->force_workflow;
        }

//        $this->model->setScenario(Event::SCENARIO_DESCRIPTION);

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post())) {
            $community->force_workflow = \Yii::$app->request->post()['Community']['force_workflow'];
            $community->save(false);

            if ($this->model->save(false)) {
                $community->saveDashboardCommunity();
            }
            return $this->goToNextPart();
        }

        $this->setParamsForView();
        return $this->render('community', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'community' => $community

        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionEmails($id)
    {
        Url::remember();
        $newsletterModule = \Yii::$app->getModule('newsletter');
        $decodedEmail = null;
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();

            $decodedEmails = $mailService->getEmailList(33, ['tags' => 'template_event_invite']);
            if (!empty($decodedEmails->Items)) {
                $decodedEmail = $mailService->getEmail(33, $decodedEmails->Items[0]->idMessage);
            }
        }

        $this->model = $this->findModel($id);
        $this->model->setScenario(Event::SCENARIO_WIZARD_EMAILS);
        $emailTemplates = $this->model->eventEmailTemplates;
        if (empty($emailTemplates)) {
            $emailTemplates = new EventEmailTemplates();
            $emailTemplates->event_id = $id;
        }

        $this->detachCwhBehavior();
        $this->model->detachBehavior('seoBehavior');

        $emailTemplatesClone = clone $emailTemplates;
        if (Yii::$app->getRequest()->post() && $emailTemplates->load(\Yii::$app->request->post()) && $emailTemplatesClone->load(\Yii::$app->request->post()) && $emailTemplates->validate()) {
            if ($emailTemplatesClone->save(false)) {
                $this->model->save(false);
                return $this->goToNextPart();
            }
        }


        $this->setParamsForView();
        return $this->render('emails', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'email' => $decodedEmail,
            'emailTemplates' => $emailTemplates
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionInvite($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->model->detachBehavior('seoBehavior');

        $internalList = $this->model->getEventInternalLists()->one();
        if (empty($internalList)) {
            $internalList = new \open20\amos\events\models\EventInternalList();
        }

        $modelSearch = new UserEventSearch();
        if (!empty($internalList)) {
            parse_str(($internalList->search_params), $query_params);
//            pr($query_params);
            $modelSearch->load($query_params);
//            pr($modelSearch->attributes);
        } else {
            $modelSearch->event_id = $id;
            $modelSearch->type = UserEventSearch::SEARCH_TYPE_ALL;
        }
        if ($internalList->isNewRecord) {
            $modelSearch->type = UserEventSearch::SEARCH_TYPE_ALL;
        }

        $this->detachCwhBehavior();
        $this->model->setScenario(Event::SCENARIO_WIZARD_INVITE);

        $post = \Yii::$app->request->post();
        if ($post) {
            if ($internalList->load($post) && $internalList->validate()) {
                $internalList->search_params = $post['EventInternalList']['search_params'];
                if ($internalList->save(false)) {
                    return $this->goToNextPart();
                }
            } else {
                \Yii::$app->session->addFlash('danger', "Devi ricercare gli utenti da invitare e assegnare un titolo alla ricerca effettuata prima di proseguire.");
                if (!empty($internalList)) {
                    parse_str($post['EventInternalList']['search_params'], $query_params);
                    $modelSearch->load($query_params);
                }
//            pr($internalList->getErrors());
            }
        }

        $this->setParamsForView();
        return $this->render('invite', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'modelSearch' => $modelSearch,
            'internalList' => $internalList
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFinish($id)
    {
        Url::remember();

        $this->model = $this->findModel($id);
        $this->detachCwhBehavior();

        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $templates = $utility->getCmsTemplates();
        $titleTemplate = '';
        foreach ($templates as $template) {
            if ($template['id'] == $this->model->eventLanding->luya_template_id) {
                $titleTemplate = $template['title'];
            }
        }


        $icons = null;
        $community = Community::findOne($this->model->community_id);
        if ($community) {
            $icons = $community->amosWidgetsIcons;
        }
//        $this->model->setScenario(Event::SCENARIO_DESCRIPTION);

        if (Yii::$app->getRequest()->post() && $this->model->load(Yii::$app->getRequest()->post()) && $this->model->save()) {
            return $this->goToNextPart();
        }

        $this->setParamsForView();
        return $this->render('finish', [
            'model' => $this->model,
            'moduleEvents' => $this->eventsModule,
            'icons' => $icons,
            'titleTemplate' => $titleTemplate
        ]);
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


    public function actionGetValues()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $field = $parents[0];
                $results = UserEventSearch::getAttributesValues($field);
                foreach ($results as $id => $val) {
                    $out [] = ['id' => $id, 'name' => $val];
                }

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    /**
     * @return string
     */
    public function actionRenderSearchAjax()
    {
        $post = \Yii::$app->request->post();

        parse_str(urldecode($post['data']), $query_params);
        $modelSearch = new UserEventSearch();
        $form = new ActiveForm();

        $queryParamsToUpdate = $this->updateParams($query_params);
//        pr($queryParamsToUpdate);
        $modelSearch->load($queryParamsToUpdate);
        $count = count($modelSearch->field);
        if ($post['plus'] == 1) {
            $count++;
        }

        return $this->renderAjax('_search_users', ['modelSearch' => $modelSearch, 'form' => $form, 'count' => $count]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSearchUsers()
    {
        $post = \Yii::$app->request->post();
        parse_str(urldecode($post['data']), $query_params);
        $modelSearch = new UserEventSearch();
        $query = $modelSearch->search($query_params, false);
        $count = $query->count();
//        pr($query->createCommand()->rawSql);
//        pr($post);
//        pr($query_params);

        $isWizard = ($post['wizard'] == '1') ? true : false;
        $form = new ActiveForm();
        $internalList = new \open20\amos\events\models\EventInternalList();
        $internalList->event_id = $modelSearch->event_id;
        $queryBase = clone $query;
        $internalList->query_sql = $query
            ->select(new Expression("user.*, user_profile.*, istat_comuni.nome as 'comune', istat_province.nome as 'provincia', user_profile_age_group.age_group as eta"))
            ->leftJoin('user_profile_age_group', 'user_profile_age_group.id = user_profile.user_profile_age_group_id')
            ->leftJoin('istat_comuni', 'istat_comuni.id = user_profile.nascita_comuni_id')
            ->leftJoin('istat_province', 'istat_province.id = user_profile.nascita_province_id')
            ->createCommand()->rawSql;
        $internalList->active_query = urlencode(serialize($queryBase));
        $internalList->search_params = urldecode($post['data']);
        $internalList->n_user_found = $count;
        return $this->renderAjax('_results_search', [
            'count' => $count,
            'internalList' => $internalList,
            'form' => $form,
            'modelSearch' => $modelSearch,
            'saveAjax' => !$isWizard,
        ]);

    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSaveList()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = \Yii::$app->request->post();
        parse_str(urldecode($post['data']), $query_params);
        $internalList = new \open20\amos\events\models\EventInternalList();
        if ($post && $internalList->load($query_params)) {
            $internalList->search_params = $post['searchParams'];
            if ($internalList->save(false)) {
                return ['success' => true, 'message' => AmosEvents::t('amosEvents', 'Lista salvata con successo')];
            }
        }
        return ['success' => false, 'message' => AmosEvents::t('amosEvents', 'Errore nel salvataggio della lista')];
    }

    /**
     * @param $query_params
     * @return mixed
     */
    public function updateParams($query_params)
    {
        $queryParamsToUpdate = $query_params;
        $queryParamsToUpdate['UserEventSearch']['field'] = null;
        $queryParamsToUpdate['UserEventSearch']['includeExclude'] = null;
        $queryParamsToUpdate['UserEventSearch']['value'] = null;
        $i = 1;
        foreach ($query_params['UserEventSearch']['field'] as $key => $field) {
            $queryParamsToUpdate['UserEventSearch']['field'][$i] = $field;
            $queryParamsToUpdate['UserEventSearch']['includeExclude'][$i] = $query_params['UserEventSearch']['includeExcluse'];
            $queryParamsToUpdate['UserEventSearch']['value'][$i][$i] = $query_params['UserEventSearch']['value'][$key];
            $i++;
        }
        return $queryParamsToUpdate;
    }


    /**
     * @param null $q
     * @param null $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionAjaxEventTagList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $tagToSkip = [];
        $isOperator = \Yii::$app->user->can('EVENT_DG_OPERATOR');

        $root = Tag::find()->andWhere(['codice' => Event::ROOT_TAG_CUSTOM_EVENTS])->one();
        if ($id) {
            $queryCurrentTag = EntitysTagsMm::find()
                ->andWhere(['classname' => Event::className()])
                ->andWhere(['record_id' => $id]);
            $currentTags = $queryCurrentTag->all();
            foreach ($currentTags as $tag) {
                $tagToSkip = $tag->tag_id;
            }

            if ($isOperator) {
                $currentGroup = EventsUtility::getCurrentDg();
                if ($currentGroup) {
                    // get event  aren't of current dgs
                    $queryCurrentDg = Event::find()->select('event.id')->andWhere(['event_group_referent_id' => $currentGroup->id]);
                    $queryCurrentTagDg = EntitysTagsMm::find()
                        ->select('entitys_tags_mm.tag_id')
                        ->andWhere(['classname' => Event::className()])
                        ->andWhere(['record_id' => $queryCurrentDg]);
                }
            }
        }
        if (!is_null($q)) {
            $query = new Query();
            $query->select(new Expression("id, nome AS text"))
                ->from('tag')
                ->andWhere(['root' => $root->id])
                ->andWhere(['!=', 'id', $root->id])
                ->andWhere(['LIKE', 'nome', $q])
                ->andWhere(['not in', 'id', $tagToSkip])
                ->andWhere(['is', 'deleted_at', null])
                ->limit(20);
            if ($isOperator) {
                $query->andWhere(['id' => $queryCurrentTagDg]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function actionSendEmailTest($id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $newsletterModule = \Yii::$app->getModule('newsletter');
        $decodedEmail = null;
        if ($newsletterModule) {
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $decodedEmails = $mailService->sendEmailToRecipient(4749, \Yii::$app->request->post('email'));
        }
        return $decodedEmails;

    }

    /**
     * @param $model
     */
    public function createUpdateCommunity($model)
    {
        $isCreate = false;
        if (is_null($model->community_id)) {
            $managerStatus = CommunityUserMm::STATUS_ACTIVE;//$this->getManagerStatus($model, $oldAttributes);
            $ok = EventsUtility::createCommunity($model, $managerStatus, CommunityType::COMMUNITY_TYPE_CLOSED);
            $model->show_community = false;
            $model->save(false);
            $isCreate = true;
        } else {
            $ok = EventsUtility::updateCommunity($model);
        }
    }

    /**
     * @return string
     */
    public function actionNotAssociatedForbidden()
    {
        return $this->render('not-associated-forbidden');
    }


}