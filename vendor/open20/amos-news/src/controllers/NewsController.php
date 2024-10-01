<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news\controllers;

use amos\sitemanagement\models\SiteManagementSlider;
use open20\amos\attachments\models\File;
use open20\amos\comments\AmosComments;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\PermissionHelper;
use open20\amos\core\helpers\StringHelper;
use open20\amos\core\icons\AmosIcons;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\admin\AmosAdmin;
use open20\amos\events\models\Event;
use open20\amos\news\AmosNews;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\models\base\NewsRelatedEventMm;
use open20\amos\news\models\News;
use open20\amos\news\widgets\icons\WidgetIconNewsCreatedBy;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\View;

/**
 * Class NewsController
 *
 * NewsController implements the CRUD actions for News model.
 *
 * @package open20\amos\news\controllers
 */
class NewsController extends CrudController {

    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

    public $layout = 'list'; // @var string $layout

    /** @var AmosNews $newsModule */
    public $newsModule = null; // @var AmosNews|null $newsModule
    public $moduleCwh;
    public $scope;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->initDashboardTrait();

        $this->setModelObj(AmosNews::instance()->createModel('News'));
        $this->setModelSearch(AmosNews::instance()->createModel('NewsSearch'));

        ModuleNewsAsset::register(Yii::$app->view);

        $this->newsModule = Yii::$app->getModule(AmosNews::getModuleName());
        $this->moduleCwh = Yii::$app->getModule('cwh');

        $this->scope = null;
        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }

        // $this->viewList = [
        //     'name' => 'list',
        //     'label' => AmosIcons::show('view-list').Html::tag('p', AmosNews::t('amosnews', 'List')),
        //     'url' => '?currentView=list',
        // ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
            'url' => '?currentView=grid',
        ];

        $this->viewIcon = [
            'name' => 'icon',
            'label' => AmosIcons::show('view-module') . Html::tag('p', Yii::t('amoscore', 'Card')),
            'url' => '?currentView=icon'
        ];

        $defaultViews = [
            //'list' => $this->viewList,
            'icon' => $this->viewIcon,
            'grid' => $this->viewGrid,
        ];

        $availableViews = [];
        foreach ($this->newsModule->defaultListViews as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        // Disable mapParams configuration if the "enableMap" parameter is disabled in the module
        if (!empty($this->newsModule->params['mapParams'][$this->id]['enable']) && !$this->newsModule->enableMap) {
            $this->newsModule->params['mapParams'][$this->id]['enable'] = false;
        }

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Lists all the validated news.
     * @return string
     */
    public function actionIndex($layout = null) {
        return $this->redirect(['/news/news/all-news']);

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Tutte le notizie'));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
                        'index',
                        [
                            'dataProvider' => $this->getDataProvider(),
                            'model' => $this->getModelSearch(),
                            'currentView' => $this->getCurrentView(),
                            'availableViews' => $this->getAvailableViews(),
                            'url' => ($this->url) ? $this->url : null,
                            'parametro' => ($this->parametro) ? $this->parametro : null
                        ]
        );
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $rules = [
            [
                'allow' => true,
                'actions' => [
                    'own-news',
                    'to-validate-news',
                    'all-news',
                    'admin-all-news',
                    'own-interest-news',
                    'redaction-all-news',
                ],
                'roles' => ['AMMINISTRATORE_NEWS']
            ],
            [
                'allow' => true,
                'actions' => [
                    'own-news',
                    'view',
                    'all-news',
                    'admin-all-news',
                    'own-interest-news',
                    'redaction-all-news'
                ],
                'roles' => ['REDATTORECMS']
            ],
            [
                'allow' => true,
                'actions' => [
                    'validate-news',
                    'reject-news',
                ],
                'roles' => ['AMMINISTRATORE_NEWS', 'FACILITATORE_NEWS', 'FACILITATOR']
            ],
            [
                'allow' => true,
                'actions' => [
                    'own-news',
                    'all-news',
                    'own-interest-news',
                    'extract-news',
                    'clone-news',
                    'search-news-title',
                    'update-grid-view',
                    'save-user-tags'
                ],
                'roles' => ['LETTORE_NEWS']
            ],
            [
                'allow' => true,
                'actions' => [
                    'to-validate-news',
                    'all-news',
                    'validate-news',
                    'own-interest-news',
                    'reject-news',
                ],
                'roles' => ['VALIDATORE_NEWS']
            ],
            [
                'allow' => true,
                'actions' => [
                    'own-news',
                    'to-validate-news',
                    'all-news',
                    'own-interest-news'
                ],
                'roles' => ['FACILITATORE_NEWS']
            ],
            [
                'allow' => true,
                'actions' => [
                    'to-validate-news',
                    'validate-news',
                    'reject-news',
                ],
                'roles' => ['NewsValidateOnDomain']
            ],
        ];
        if (!$this->newsModule->disableBefeControllerRules) {
            $rules[] = [
                'allow' => true,
                'actions' => [
                    ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? 'all-news' : 'nothing'),
                    ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? 'index' : 'nothingindex'),
                    ((!empty(\Yii::$app->params['befe']) && \Yii::$app->params['befe'] == true) ? 'view' : 'nothingread')
                ],
                'matchCallback' => function ($rule, $action) {
                    if (in_array($action->id, ['all-news', 'index']))
                        return true;
                    if ($action->id != 'view')
                        return false;
                    $id = (!empty(\Yii::$app->request->get()['id']) ? Yii::$app->request->get()['id'] : null);
                    if (!empty($id)) {
                        $model = News::findOne($id);

                        if (!empty($model) && $model->primo_piano == 1) {
                            return true;
                        }
                    }
                    return false;
                }
            ];
        }
        $behaviors = ArrayHelper::merge(
                        parent::behaviors(),
                        [
                            'access' => [
                                'class' => AccessControl::class,
                                'rules' => $rules
                            ],
                            'verbs' => [
                                'class' => VerbFilter::class,
                                'actions' => [
                                    'delete' => ['post', 'get']
                                ],
                            ],
                        ]
        );

        return $behaviors;
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $newsPageTitle News page title (ie. Created by news, ...)
     */
    protected function setTitleAndBreadcrumbs($newsPageTitle) {
        $this->setNetworkDashboardBreadcrumb();
        Yii::$app->session->set('previousTitle', $newsPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        //Yii::$app->view->title                   = $newsPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $newsPageTitle];
    }

    /**
     *
     */
    public function setNetworkDashboardBreadcrumb() {
        if (!empty($this->scope)) {
            if (isset($this->scope['community'])) {
                $communityId = $this->scope['community'];
                $community = \open20\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = AmosNews::t('amosnews', "Dashboard") . ' ' . $community->name;
                $dasbboardCommunityUrl = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = ['label' => $dashboardCommunityTitle, 'url' => $dasbboardCommunityUrl];
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        $moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());

        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosNews::t('amosnews', 'Notizie');
            $urlLinkAll = '';

            $labelSigninOrSignup = AmosNews::t('amosnews', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosNews::t(
                            'amosnews',
                            '#beforeActionCtaLoginRegisterTitle',
                            ['platformName' => \Yii::$app->name]
            );
            $labelSignin = AmosNews::t('amosnews', '#beforeActionCtaLogin');
            $titleSignin = AmosNews::t(
                            'amosnews',
                            '#beforeActionCtaLoginTitle',
                            ['platformName' => \Yii::$app->name]
            );

            $labelLink = $labelSigninOrSignup;
            $titleLink = $titleSigninOrSignup;
            $socialAuthModule = Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                            $labelLink,
                            isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon'] : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                            [
                                'title' => $titleLink
                            ]
            );
            $subTitleSection = Html::tag(
                            'p',
                            AmosNews::t(
                                    'amosnews',
                                    '#beforeActionSubtitleSectionGuest',
                                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                            )
            );
        } else {
            $titleSection = AmosNews::t('amosnews', 'Notizie');
            $labelLinkAll = AmosNews::t('amosnews', 'Tutte le notizie');
            $urlLinkAll = '/news/news/all-news';
            $titleLinkAll = AmosNews::t('amosnews', 'Visualizza la lista delle notizie');

            $subTitleSection = Html::tag('p', AmosNews::t('amosnews', '#beforeActionSubtitleSectionLogged'));
        }


        $labelCreate = AmosNews::t('amosnews', 'Nuova');
        $titleCreate = AmosNews::t('amosnews', 'Crea una nuova notizia');
        $labelManage = AmosNews::t('amosnews', 'Gestisci');
        $titleManage = AmosNews::t('amosnews', 'Gestisci le notizie');
        $urlCreate = '/news/news/create';
        if ($moduleNews->enableOnlyRedactional) {
            $urlCreate .= '?isRedactional=1';
            $labelLinkAll = '';
            $urlLinkAll = '';
            $titleLinkAll = '';
        }
        $urlManage = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'news',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        // Lasciare qui questo if e return perché se no va in loop...
        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnLabel() {
        $moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());

        $createurl = '/news/news/create';
        if ($moduleNews->enableOnlyRedactional) {
            $createurl .= '?redactional=1';
        }
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosNews::t('amosnews', 'Nuova'),
            'urlCreateNew' => [(array_key_exists("noWizardNewLayout", Yii::$app->params) ? $createurl : '/news/news-wizard/introduction')]
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams() {
        $this->setCreateNewBtnLabel();
        Yii::$app->session->set(AmosNews::beginCreateNewSessionKey(), Url::previous());
    }

    /**
     * Action for search all validated news.
     *
     * @return string
     */
    public function actionNotizie() {
        Url::remember();

        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams();

        return $this->render(
                        'notizie',
                        [
                            'dataProvider' => $this->getDataProvider(),
                            'currentView' => $this->getAvailableView('list'),
                        ]
        );
    }

    /**
     * Displays a single News model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        /** @var News $model */
        $model = $this->findModel($id);

        // Check permission, if user can see content
        if (method_exists(PermissionHelper::class, 'canViewContent')) {
            if (!PermissionHelper::canViewContent($model)) {
                throw new NotFoundHttpException();
            }
        }

        $events = [];
        $dataProviderEvents = null;
        if ($this->newsModule->enableRelateEvents) {
            $correlatedEvent = NewsRelatedEventMm::find()->andWhere(['news_id' => $id])->select('event_id')->asArray()->all();
            foreach ($correlatedEvent as $ids) {
                $events[] = $ids['event_id'];
            }
            $events = Event::find()
                    ->andWhere(['in', 'id', $events])
                    ->andWhere(['status' => 'EventWorkflow/PUBLISHED']);
            $dataProviderEvents = new ActiveDataProvider([
                'query' => $events
            ]);
        }

        if (isset(Yii::$app->params['isPoi']) && Yii::$app->params['isPoi'] == true) {
            if ($id == 2579) {
                $cwhActiveQuery = new CwhActiveQuery(News::class);
                $queryUsers = $cwhActiveQuery->getRecipients(
                        $model->regola_pubblicazione,
                        $model->tagValues,
                        $model->destinatari
                );
                $users = ArrayHelper::map($queryUsers->all(), 'id', 'id');
                if (!in_array(Yii::$app->user->id, $users)) {
                    return $this->redirect('\dashboard');
                }
            }
        }

        $this->setUpLayout('detail-news');
        $this->view->params['containerFullWidth'] = true;
        $this->setMetaTags();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idNews' => $id]);
        }

        return $this->render(
                        'view',
                        [
                            'currentView' => $this->getCurrentView(),
                            'events' => $events,
                            'dataProviderEvents' => $dataProviderEvents,
                            'model' => $model,
                            'moduleCwh' => $this->moduleCwh,
                            'scope' => $this->scope
                        ]
        );
    }

    /**
     * @param int $id News id.
     * @return \yii\web\Response
     */
    public function actionValidateNews($id, $uid = null) {
        // check if the user is the right
        if (($uid != null) && ($uid != Yii::$app->user->id)) {
            Yii::$app->session->addFlash(
                    'danger',
                    AmosNews::t(
                            'amosnews',
                            '#you_are_not_authorized_for_this'
                    )
            );

            return $this->redirect(['/']);
        }

        $news = News::findOne($id);
        try {
            $news->sendToStatus(News::NEWS_WORKFLOW_STATUS_VALIDATO);
            $ok = $news->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosNews::t('amosnews', 'News validated!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosNews::t('amosnews', '#ERROR_WHILE_VALIDATING_NEWS'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }

        return $this->redirect(Url::previous());
    }

    /**
     * @param $model
     * @param $redirect
     * @return \yii\web\Response
     */
    public function setDaValidareStatus($model, $redirect) {
        $news = $model;
        try {
            $news->sendToStatus(News::NEWS_WORKFLOW_STATUS_DAVALIDARE);
            $ok = $news->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosNews::t('amosnews', 'News validated!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosNews::t('amosnews', '#ERROR_WHILE_VALIDATING_NEWS'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }

        return $redirect;
    }

    /**
     * @param int $id News id.
     * @return \yii\web\Response
     */
    public function actionRejectNews($id, $uid = null) {
        // check if the user is the right
        if (($uid != null) && ($uid != Yii::$app->user->id)) {
            Yii::$app->session->addFlash(
                    'danger',
                    AmosNews::t(
                            'amosnews',
                            '#you_are_not_authorized_for_this'
                    )
            );

            return $this->redirect(['/']);
        }

        $newsModel = $this->newsModule->model('News');
        $news = $newsModel::findOne($id);
        try {
            $news->sendToStatus(News::NEWS_WORKFLOW_STATUS_BOZZA);
            $ok = $news->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosNews::t('amosnews', 'News rejected!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosNews::t('amosnews', '#ERROR_WHILE_REJECTING_NEWS'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }

        return $this->redirect(Url::previous());
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate($urlRedirect = null) {
        $this->setUpLayout('form');
        $this->registerConfirmJs();

        Yii::$app->view->params['textHelp']['filename'] = 'create_news_dashboard_description';

        $enableAgid = $this->newsModule->enableAgid;
        $enableOtherNewsCategories = $this->newsModule->enableOtherNewsCategories;

        if ($this->newsModule->hidePubblicationDate) {
            $scenario = News::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE;
        } else {
            $scenario = News::SCENARIO_CREATE;
        }

        /** @var News $model */
        $model = $this->newsModule->createModel('News', ['scenario' => $scenario]);
        if (($enableAgid) && ($this->newsModule->request_publish_on_hp == false)) {
            $model->primo_piano = 1;
            $model->in_evidenza = 1;
        }
        if (!empty(Yii::$app->request->get('category_id'))) {
            $model->news_categorie_id = Yii::$app->request->get('category_id');
        }
        $this->model = $model;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $validateOnSave = true;
                if ($model->status == News::NEWS_WORKFLOW_STATUS_DAVALIDARE) {
                    $model->status = News::NEWS_WORKFLOW_STATUS_BOZZA;
                    $model->save();
                    $model->status = News::NEWS_WORKFLOW_STATUS_DAVALIDARE;
                    $validateOnSave = false;
                }

                if ($model->status == News::NEWS_WORKFLOW_STATUS_VALIDATO) {
                    $model->status = News::NEWS_WORKFLOW_STATUS_BOZZA;
                    $model->save();
                    $model->status = News::NEWS_WORKFLOW_STATUS_VALIDATO;
                    $validateOnSave = false;
                }

                if ($model->save($validateOnSave)) {

                    // save news_agid_person_mm
                    if ($enableAgid) {
                        $model->createNewsAgidPersonMm();
                        $model->createNewsRelatedNewsMm();
                        $model->createNewsRelatedDocumentiMm();
                        $model->createNewsRelatedAgidServiceMm();
                    }
                    if ($enableOtherNewsCategories) {
                        $model->saveOtherNewsCategories();
                    }

                    if ($this->newsModule->enableFreeTags) {
                        \open20\amos\tag\utility\TagFreeUtility::set($model->id, get_class($model), $model->tag_free);
                    }

                    /** @var AmosComments $moduleComments */
                    $moduleComments = \Yii::$app->getModule('comments');
                    if ($model->checkCommentsModeration()) {
                        $moduleComments->saveCommentModeration($model->modelClassName, $model->id);
                    }

                    Yii::$app->getSession()->addFlash(
                            'success',
                            AmosNews::t('amosnews', 'Notizia salvata con successo.')
                    );

                    $redirectToUpdatePage = false;

                    if (Yii::$app->getUser()->can('NEWS_UPDATE', ['model' => $model])) {
                        $redirectToUpdatePage = true;
                    }

                    if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                        $redirectToUpdatePage = true;
                    }

                    if ($urlRedirect) {
                        return $this->redirect([$urlRedirect, 'id' => $model->id]);
                    } else if ($redirectToUpdatePage) {
                        return $this->redirect(['/news/news/update', 'id' => $model->id]);
                    } else {
                        return $this->redirect('/news/news/own-news');
                    }
                } else {
                    Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio')
                    );

                    return $this->render(
                                    'create',
                                    [
                                        'model' => $model,
                                        'moduleCwh' => $this->moduleCwh,
                                        'scope' => $this->scope
                                    ]
                    );
                }
            } else {
                Yii::$app->getSession()->addFlash(
                        'danger',
                        AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi")
                );
            }
        }

        $siteManagementModule = ($enableAgid ? \Yii::$app->getModule('sitemanagement') : null);

        return $this->render(
                        'create',
                        [
                            'model' => $model,
                            'moduleCwh' => $this->moduleCwh,
                            'scope' => $this->scope,
                            'siteManagementModule' => $siteManagementModule
                        ]
        );
    }

    /**
     * Il metodo registra, all'evento di READY, il javascript di conferma su ogni elemento su cui è necessario.
     */
    protected function registerConfirmJs() {
        $btnIds = [
            'new-news-attachment'
        ];

        $confirmJs = $this->createConfirmJsString($btnIds);
        Yii::$app->view->registerJs($confirmJs, View::POS_READY);
    }

    /**
     * Il metodo crea la stringa javascript pronta da registrare con tutti i listener sull'evento di click che chiedono la conferma
     * concatenando tutti i javascript per ciascun id presente nell'array di stringhe passato come parametro.
     *
     * @param array $elementIds
     * @return string
     */
    protected function createConfirmJsString($elementIds) {
        $confirmJsString = '';
        foreach ($elementIds as $elementId) {
            $confirmJsString .= $this->javascriptConfirm($elementId);
        }

        return $confirmJsString;
    }

    /**
     * TBD manca la traduzione!!!!

     * Il metodo crea il listener sull'evento di click per un qualche elemento del DOM.
     * L'evento mostra un messaggio e chiede conferma.
     *
     * @param $buttonId
     * @return string
     */
    protected function javascriptConfirm($elementId) {
        return "
      $('#" . $elementId . "').click(function (e) {
        return confirm('Attenzione! Si sta per lasciare la pagina. Salvare tutti i dati, altrimenti andranno persi.');
      });
    ";
    }

    /**
     * Updates an existing News model.
     *
     * @param integer $id
     * @param bool|false $backToEditStatus Save the model with status Editing in progress before form rendering
     *
     * @return mixed
     */
    public function actionUpdate($id, $backToEditStatus = false, $urlRedirect = null) {
        $this->setUpLayout('form');

        /** @var News $model */
        $model = $this->findModel($id);
        $this->model = $model;
        $model->loadOtherNewsCategories();

        if ($this->newsModule->enableFreeTags) {
            $model->tag_free = \open20\amos\tag\utility\TagFreeUtility::get($model->id, get_class($model));
        }

        $this->registerConfirmJs();

        Yii::$app->view->params['textHelp']['filename'] = 'create_news_dashboard_description';

        $enableAgid = $this->newsModule->enableAgid;
        $enableOtherNewsCategories = $this->newsModule->enableOtherNewsCategories;

        $redirectToUpdatePage = false;
        if (Yii::$app->getUser()->can('NEWS_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->request->post()) {
            $previousStatus = $model->status;
            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {
                    if ($model->save()) {

                        if ($this->newsModule->enableFreeTags) {
                            \open20\amos\tag\utility\TagFreeUtility::set($model->id, get_class($model), $model->tag_free);
                        }

                        // update news_agid_person_mm
                        if ($enableAgid) {
                            $model->updateNewsAgidPersonMm();
                            $model->updateNewsRelatedNewsMm();
                            $model->updateNewsRelatedDocumentiMm();
                            $model->updateNewsRelatedAgidServiceMm();
                        }

                        if ($enableOtherNewsCategories) {
                            $model->saveOtherNewsCategories();
                        }

                        /** @var AmosComments $moduleComments */
                        $moduleComments = Yii::$app->getModule('comments');
                        if ($model->checkCommentsModeration()) {
                            $moderationValue = \Yii::$app->request->post('comments_moderation');
                            $moduleComments->saveCommentModeration($model->modelClassName, $model->id, $moderationValue);
                        }

                        if ($urlRedirect) {
                            return $this->redirect($urlRedirect);
                        }
                        $redirectToUpdatePage = false;
                        if (Yii::$app->getUser()->can('NEWS_UPDATE', ['model' => $model])) {
                            $redirectToUpdatePage = true;
                        }

                        if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                            $redirectToUpdatePage = true;
                        }

                        if ($redirectToUpdatePage) {
                            Yii::$app->getSession()->addFlash(
                                    'success',
                                    AmosNews::t('amosnews', 'Notizia aggiornata con successo.')
                            );

                            if (strpos($model->status, 'VALIDATO')) {
                                return $this->redirect(['/news/news/update', 'id' => $model->id]);
                            } elseif (strpos($model->status, 'BOZZA') && strpos($previousStatus, 'DAVALIDARE')) {
                                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
                            } else {
                                return $this->redirect(['/news/news/update', 'id' => $model->id]);
                            }
                        } else {
                            return $this->redirect('/news/news/own-news');
                        }
                    } else {
                        Yii::$app->getSession()->addFlash(
                                'danger',
                                AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio')
                        );
                        return $this->render(
                                        'update',
                                        [
                                            'model' => $model,
                                            'moduleCwh' => $this->moduleCwh,
                                            'scope' => $this->scope
                                        ]
                        );
                    }
                } else {
                    Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi")
                    );
                }
            }
        } else {
            if ($backToEditStatus && ($model->status != $model->getDraftStatus() && !$redirectToUpdatePage)) {
                $model->status = $model->getDraftStatus();
                if ($model->validate()) {
                    $ok = $model->save();
                    if (!$ok) {
                        Yii::$app->getSession()->addFlash(
                                'danger',
                                AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio')
                        );
                    }
                } else {
                    Yii::$app->getSession()->addFlash(
                            'danger',
                            AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi")
                    );
                }
            }
        }

        /**
         * Agid modules enabled
         */
        if ($enableAgid) {
            $siteManagementModule = \Yii::$app->getModule('sitemanagement');
            $slider_image = null;
            $dataProviderSliderElemImage = null;
            $slider_video = null;
            $dataProviderSliderElemVideo = null;

            if (!is_null($siteManagementModule)) {
                $slider_image = $this->model->sliderImage;
                if (empty($slider_image)) {
                    $slider_image = new SiteManagementSlider();
                    $slider_image->section_id = null;
                    $slider_image->title = $this->model->getTitleSlider();
                    $slider_image->save(false);
                    $this->model->image_site_management_slider_id = $slider_image->id;
                    $this->model->save(false);
                }

                $dataProviderSliderElemImage = new ActiveDataProvider(['query' => $slider_image->getSliderElems()->orderBy('order ASC')]);

                $slider_video = $this->model->sliderVideo;

                if (empty($slider_video)) {
                    $slider_video = new SiteManagementSlider();
                    $slider_video->section_id = null;
                    $slider_video->title = $this->model->getTitleSlider();
                    $slider_video->save(false);
                    $this->model->video_site_management_slider_id = $slider_video->id;
                    $this->model->save(false);
                }

                $dataProviderSliderElemVideo = new ActiveDataProvider(['query' => $slider_video->getSliderElems()->orderBy('order ASC')]);
            }

            return $this->render(
                            'update',
                            [
                                'model' => $model,
                                'moduleCwh' => $this->moduleCwh,
                                'scope' => $this->scope,
                                'siteManagementModule' => $siteManagementModule,
                                'slider_image' => $slider_image,
                                'dataProviderSliderElemImage' => $dataProviderSliderElemImage,
                                'slider_video' => $slider_video,
                                'dataProviderSliderElemVideo' => $dataProviderSliderElemVideo
                            ]
            );
        }

        if ($redirectToUpdatePage) {
            return $this->render(
                            'update',
                            [
                                'model' => $model,
                                'moduleCwh' => $this->moduleCwh,
                                'scope' => $this->scope
                            ]
            );
        }

        return $this->redirect('/news/news/own-news');
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id, $urlRedirect = null) {
        /** @var News $model */
        $model = $this->findModel($id);
        $model->delete();

        if (!$model->hasErrors()) {
            Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'Notizia cancellata correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash(
                    'danger',
                    AmosNews::t('amosnews', 'Non sei autorizzato a cancellare la notizia.')
            );
        }

        if ($urlRedirect) {
            return $this->redirect($urlRedirect);
        }

        $redirectSession = Yii::$app->session->get(AmosNews::beginCreateNewSessionKey());
        if (!empty($redirectSession)) {
            return $this->redirect(Yii::$app->session->get(AmosNews::beginCreateNewSessionKey()));
        } else {
            return $this->redirect(\Yii::$app->request->referrer);
        }
    }

    /**
     * Action to search only for their news
     *
     * @return string
     */
    public function actionOwnNews($currentView = null) {
        Url::remember();

        $getParams = Yii::$app->request->get();
        $viewToRender = 'index';
        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->setDataProvider($this->getModelSearch()->searchOwnNews(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie create da me'));
        $this->setListViewsParams();
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosNews::t(
                        'amosnews',
                        '{iconaTabella}' . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                        [
                            'iconaTabella' => AmosIcons::show('view-list-alt')
                        ]
                ),
                'url' => '?currentView=grid'
            ],
        ]);

        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setUpLayout('list');
        if ($getParams['map'] && empty($getParams['currentView'])) {
            $this->setupMapView();
            $viewToRender = 'map';
        }
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Notizie create da me');
        }

        // Agid news dataProvider
        if ($this->newsModule->enableAgid) {

            $this->setAgidNewsDataProvider();
        }

        return $this->render($viewToRender, [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Action to search to validate news.
     *
     * @return string
     */
    public function actionToValidateNews() {
        Url::remember();

        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->setDataProvider($this->getModelSearch()->searchToValidateNews(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie da validare'));
        $this->setListViewsParams();

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosNews::t(
                        'amosnews',
                        '{iconaTabella}' . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                        [
                            'iconaTabella' => AmosIcons::show('view-list-alt')
                        ]
                ),
                'url' => '?currentView=grid'
            ]
        ]);

        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setUpLayout('list');

        $this->view->params['hideMapSearch'] = true;
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Notizie da validare');
        }

        // Agid news dataProvider
        if ($this->newsModule->enableAgid) {

            $this->setAgidNewsDataProvider();
        }

        return $this->render(
                        'index',
                        [
                            'dataProvider' => $this->getDataProvider(),
                            'model' => $this->getModelSearch(),
                            'currentView' => $this->getCurrentView(),
                            'availableViews' => $this->getAvailableViews(),
                            'url' => ($this->url) ? $this->url : null,
                            'parametro' => ($this->parametro) ? $this->parametro : null
                        ]
        );
    }

    /**
     * Action for search all news.
     *
     * @return string
     */
    public function actionAllNews($currentView = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }

        $getParams = Yii::$app->request->get();
        $viewToRender = 'index';
        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Tutte le notizie'));
        $this->setListViewsParams();
        $this->setCurrentView($this->getAvailableView($currentView));
        $this->setUpLayout('list');
        if ($getParams['map'] && empty($getParams['currentView'])) {
            $this->setupMapView();
            $viewToRender = 'map';
        }
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        if (!\Yii::$app->user->isGuest) {
            $bc = $this->model->getBullet(\open20\amos\core\record\Record::BULLET_TYPE_ALL, true);
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Tutte le notizie');
            $this->view->params['labelLinkAll'] = AmosNews::t('amosnews', 'Notizie di mio interesse');
            $this->view->params['urlLinkAll'] = AmosNews::t('amosnews', '/news/news/own-interest-news');
            $this->view->params['titleLinkAll'] = AmosNews::t('amosnews', 'Visualizza la lista delle notizie di mio interesse');
            $this->view->params['bulletCount'] = $bc;
            $this->showCloneButton();
        } else {
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Tutte le notizie');
        }

        // Agid news dataProvider
        if ($this->newsModule->enableAgid) {

            $this->setAgidNewsDataProvider();
        }

        return $this->render($viewToRender, [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * @param null $currentView
     * @return string
     */
    public function actionAdminAllNews($currentView = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }

        $this->setDataProvider($this->getModelSearch()->searchAdminAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Amministra notizie'));
        $this->setListViewsParams();

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Amministra notizie');
        }

        // Agid news dataProvider
        if ($this->newsModule->enableAgid) {

            $this->setAgidNewsDataProvider();
        }

        $this->view->params['containerFullWidth'] = true;
        $this->view->params['hideMapSearch'] = true;

        return $this->render(
                        'redaction-index',
                        [
                            'dataProvider' => $this->getDataProvider(),
                            'model' => $this->getModelSearch(),
                            'currentView' => $this->getCurrentView(),
                            'availableViews' => $this->getAvailableViews(),
                            'url' => ($this->url) ? $this->url : null,
                            'parametro' => ($this->parametro) ? $this->parametro : null
                        ]
        );
    }

    /**
     * @param null $currentView
     * @return string
     */
    public function actionRedactionAllNews($currentView = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }

        $this->setDataProvider($this->getModelSearch()->searchAdminAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie'));
        $this->setListViewsParams();

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Notizie');
        }

        // Agid news dataProvider
        if ($this->newsModule->enableAgid) {

            $this->setAgidNewsDataProvider();
        }
        $this->view->params['containerFullWidth'] = true;

        return $this->render(
                        'redaction-index',
                        [
                            'dataProvider' => $this->getDataProvider(),
                            'model' => $this->getModelSearch(),
                            'currentView' => $this->getCurrentView(),
                            'availableViews' => $this->getAvailableViews(),
                            'url' => ($this->url) ? $this->url : null,
                            'parametro' => ($this->parametro) ? $this->parametro : null
                        ]
        );
    }

    /**
     * Action for search all news.
     *
     * @return string
     */
    public function actionOwnInterestNews($currentView = null) {
        Url::remember();

        $getParams = Yii::$app->request->get();
        $viewToRender = 'index';
        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }

        $this->setDataProvider($this->getModelSearch()->searchOwnInterest(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie di mio interesse'));
        $this->setListViewsParams();
        $this->setCurrentView($this->getAvailableView($currentView));
        $this->setUpLayout('list');
        if ($getParams['map'] && empty($getParams['currentView'])) {
            $this->setupMapView();
            $viewToRender = 'map';
        }
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        if (!\Yii::$app->user->isGuest) {
            $bc = $this->model->getBullet(\open20\amos\core\record\Record::BULLET_TYPE_OWN, true);
            $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Notizie di mio interesse');
            $this->view->params['bulletCount'] = $bc;
            $this->showCloneButton();
        }

        // Agid news dataProvider
        if ($this->newsModule->enableAgid) {
            $this->setAgidNewsDataProvider();
        }

        return $this->render($viewToRender, [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : null,
                    'parametro' => ($this->parametro) ? $this->parametro : null
        ]);
    }

    /**
     * @param integer $communityId
     * @param integer $newsId
     * @param string $currentView
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionCloneNews($communityId, $newsId = null, $currentView = null) {
        if (!AmosNews::instance()->enableCloningIntoCommunities) {
            return $this->redirect(['index']);
        }
        if (empty($this->scope)) {
            $this->moduleCwh->setCwhScopeInSession(['community' => $communityId]);
        }
        if (!CommunityUtil::isLoggedCommunityManager()) {
            throw new ForbiddenHttpException(\Yii::t('amoscore', 'Non sei autorizzato a visualizzare questa pagina'));
        }

        if (!empty($newsId)) {
            /** @var News $newsToClone */
            $newsToClone = $this->findModel($newsId);
            $attachmentsToClone = File::find()
                    ->andWhere(['model' => $newsToClone->modelClassName])
                    ->andWhere(['item_id' => $newsId])
                    ->all();

            /** @var News $newsToCreate */
            $newsToCreate = $this->newsModule->createModel('News', ['scenario' => News::SCENARIO_CREATE]);
            $newsToCreate->setAttributes($newsToClone->getAttributes());
            $newsToCreate->created_by = \Yii::$app->user->id;
            $newsToCreate->status = News::NEWS_WORKFLOW_STATUS_BOZZA;
            $newsToCreate->regola_pubblicazione = 3;
            $newsToCreate->validatori = 'community-' . $communityId;

//            $newsToCreate->detachBehaviors();
            if ($newsToCreate->save(false)) {
                if (!empty($attachmentsToClone)) {
                    foreach ($attachmentsToClone as $attachmentToClone) {
                        /** @var File $attachmentToClone */
                        $attachmentToCreate = new File();
                        $attachmentToCreate->setAttributes($attachmentToClone->getAttributes());
                        $attachmentToCreate->item_id = $newsToCreate->id;
                        $attachmentToCreate->save(false);
                    }
                }
                $this->addFlash('success', AmosNews::t('amosnews', 'Notizia importata con successo.'));
                return $this->redirect(['/news/news/update', 'id' => $newsToCreate->id]);
            } else {
                $this->addFlash('danger', AmosNews::t('amosnews', 'Errore durante l\'importazione della notizia.'));
            }

            return $this->redirect(['/news/news/clone-news', 'communityId' => $communityId]);
        }

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }
        $this->setDataProvider($this->getModelSearch()->searchClone(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Importa una news dalla piattaforma'));
        $this->setListViewsParams();
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setUpLayout('list');

        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->view->params['titleSection'] = AmosNews::t('amosnews', 'Importa una news dalla piattaforma');
        $this->view->params['labelLinkAll'] = null;
        $this->view->params['urlLinkAll'] = null;
        $this->view->params['hideCreate'] = true;
        $this->view->params['hideMan'] = true;

        return $this->render(
                        'clone-news',
                        [
                            'dataProvider' => $this->getDataProvider(),
                            'model' => $this->getModelSearch(),
                            'currentView' => $this->getCurrentView(),
                            'availableViews' => $this->getAvailableViews(),
                            'url' => ($this->url) ? $this->url : null,
                            'parametro' => ($this->parametro) ? $this->parametro : null
                        ]
        );
    }

    public function actionSaveUserTags($id) {
        $tag_utente = \open20\amos\cwh\models\CwhTagOwnerInterestMm::find()
                        ->innerJoin('tag', 'tag.id = tag_id')
                        ->andWhere([
                            'record_id' => \Yii::$app->user->id,
                        ])
                        ->orderBy([
                            'tag.nome' => SORT_DESC
                        ])->all();

        $array_tag_utente = [];

        foreach ($tag_utente as $tu) {
            $array_tag_utente[] = $tu->tag_id;
        }

        $tag_news = \open20\amos\tag\models\EntitysTagsMm::find()
                        ->joinWith('tag')
                        ->andWhere([
                            'classname' => $this->model->className(),
                            'record_id' => $id,
                        ])
                        ->orderBy([
                            'tag.nome' => SORT_DESC
                        ])->all();

        $array_tag_news = [];

        foreach ($tag_news as $tn) {
            $array_tag_news[] = $tn->tag_id;
        }

        $array_diff = array_diff($array_tag_news, $array_tag_utente);

        $userProfileClass = AmosAdmin::instance()->model('UserProfile');

        foreach ($array_diff as $tagId) {
            $tagsMissing = \open20\amos\tag\models\Tag::Find()->andWhere(['id' => $tagId])->one();
            $userTag = new \open20\amos\cwh\models\CwhTagOwnerInterestMm();
            $userTag->interest_classname = 'simple-choice';
            $userTag->classname = $userProfileClass;
            $userTag->record_id = \Yii::$app->user->id;
            $userTag->tag_id = $tagId;
            $userTag->root_id = $tagsMissing->tagRoot->id;
            $userTag->save();
        }

        $this->addFlash('success', AmosNews::t('amosnews', 'Tag aggiornati correttamente'));

        $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param string $title
     * @return string
     * @throws InvalidConfigException
     */
    public function actionUpdateGridView($title, $page) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setUpLayout('list');

        $dataProvider = $this->getModelSearch()->searchClone(Yii::$app->request->getQueryParams());
        $dataProvider->query->andFilterWhere(['like', 'titolo', $title]);
        if ($page) {
            $dataProvider->pagination->page = $page;
        }
        $this->setDataProvider($dataProvider);

        return $this->renderAjax('parts/_gridView', [
                    'dataProvider' => $this->getDataProvider(),
                    'currentView' => $this->getCurrentView()
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPublic($id) {
        $model = $this->findModel($id);
        $this->layout = 'form';
        if ($this->isContentShared($id)) {
            return $this->render('public', ['model' => $model]);
        }
    }

    /**
     * @return array
     */
    public static function getManageLinks() {

        $moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());
        if ($moduleNews->enableOnlyRedactional && \Yii::$app->controller->action->id == 'redaction-all-news') {
            return [];
        }
        if (Yii::$app->controller->action->id == 'clone-news') {
            return [];
        }

        $all = false;
        if (get_class(Yii::$app->controller) != 'open20\amos\news\controllers\NewsController') {
            $all = true;
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Visualizza tutte le news'),
                'label' => AmosNews::t('amosnews', 'Tutte'),
                'url' => '/news/news/all-news'
            ];
        }

        if (Yii::$app->user->can(WidgetIconNewsCreatedBy::class)) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Visualizza le notizie create da me'),
                'label' => AmosNews::t('amosnews', 'Create da me'),
                'url' => '/news/news/own-news'
            ];
        }

        if (!$all && \Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconAllNews::class)) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Visualizza tutte le news'),
                'label' => AmosNews::t('amosnews', 'Tutte'),
                'url' => '/news/news/all-news'
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconNews::class)) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Visualizza le news di mio interesse'),
                'label' => AmosNews::t('amosnews', 'Di mio interesse'),
                'url' => '/news/news/own-interest-news',
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconNewsDaValidare::class)) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Visualizza le news da validare'),
                'label' => AmosNews::t('amosnews', 'Da validare'),
                'url' => '/news/news/to-validate-news'
            ];
        }

        if ($moduleNews->enableOnlyRedactional && \Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconAdminAllNews::class)) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Amministra tutte le news'),
                'label' => AmosNews::t('amosnews', 'Notizie'),
                'url' => '/news/news/admin-all-news'
            ];
        } else if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconAdminAllNews::class)) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Amministra tutte le news'),
                'label' => AmosNews::t('amosnews', 'Amministra'),
                'url' => '/news/news/admin-all-news'
            ];
        }


        if (\Yii::$app->user->can(\open20\amos\news\widgets\icons\WidgetIconNewsCategorie::class) && $moduleNews->showCategory) {
            $links[] = [
                'title' => AmosNews::t('amosnews', 'Visualizza le categorie delle news'),
                'label' => AmosNews::t('amosnews', 'Categorie'),
                'url' => '/news/news-categorie/index',
            ];
        }

        if (\Yii::$app->user->can('MANAGE_ORDER_FIELDS') && $moduleNews->enableCustomOrderFields) {
            $links[] = [
                'title' => AmosNews::t('amoscore', 'manage_order_fields'),
                'label' => AmosNews::t('amoscore', 'manage_order_fields'),
                'url' => '/news/news/order-fields',
            ];
        }

        return $links;
    }

    /**
     * Method to set dataProvider for news when Agid is enabled
     * set dataProvider with the sorting of the fields
     *
     * @return void
     */
    public function setAgidNewsDataProvider() {

        $dataProvider = new ActiveDataProvider([
            'query' => $this->getDataProvider()
                    ->query
                    ->joinWith('newsContentType', true)
                    ->joinWith('newsCategorie', true),
            'sort' => [
                'attributes' => [
                    'id',
                    'immagine',
                    'titolo',
                    'created_by',
                    'created_at',
                    'date_news',
                    'news_expiration_date',
                    'updated_by',
                    'updated_at',
                    'status',
                    //related columns
                    'newsContentType.name' => [
                        'asc' => ['news_content_type.name' => SORT_ASC],
                        'desc' => ['news_content_type.name' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'newsCategorie.titolo' => [
                        'asc' => ['news_categorie.titolo' => SORT_ASC],
                        'desc' => ['news_categorie.titolo' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                ],
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ]
        ]);

        $this->setDataProvider($dataProvider);
    }

    /**
     * Set meta tags useful to get the right contents in social sharing
     * @return void
     */
    public function setMetaTags() {
        $imgUrl = '/img/img_default.jpg';
        if (!is_null($this->model->newsImage)) {
            $imgUrl = $this->model->newsImage->getWebUrl('square_large', false, true);
        }
        $this->view->registerMetaTag(['property' => 'og:image', 'content' => $imgUrl]);
        //$this->view->registerMetaTag(['property' => 'og:title', 'content' => $this->model->titolo]);
        //$this->view->registerMetaTag(['property' => 'og:url', 'content' => $url]);
    }

    /**
     * Grid columns for export
     * @param $model
     * @return array[]
     */
    public function getExportRedactionColumns($model) {
        /** @var AmosNews $newsModule */
        $newsModule = \Yii::$app->getModule(AmosNews::getModuleName());
        $hidePubblicationDate = $newsModule->hidePubblicationDate;
        $hideDataRimozioneView = $newsModule->hideDataRimozioneView;

        return [
            'id' => [
                'attribute' => 'id',
                'label' => '#ID',
                'visible' => $newsModule->enableAgid,
            ],
            'status' => [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var News $model */
                    return $model->hasWorkflowStatus() ? AmosNews::t('amosnews', $model->getWorkflowStatus()->getLabel()) : '--';
                }
            ],
            'titolo' => [
                'attribute' => 'title',
                'label' => AmosNews::t('amosnews', 'Titolo'),
            ],
            'created_by' => [
                'attribute' => 'created_by',
                'label' => AmosNews::t('amosnews', 'Creatore'),
                'value' => function ($model) {
                    return $model->createdUserProfile->nomeCognome;
                },
                'format' => 'html'
            ],
            'date_news' => [
                'attribute' => 'date_news',
                'label' => 'Pubblicato il',
                'visible' => $newsModule->enableAgid,
            ],
            'news_expiration_date' => [
                'attribute' => 'news_expiration_date',
                'visible' => $newsModule->enableAgid,
            ],
            'newsContentType.name' => [
                'attribute' => 'newsContentType.name',
                'format' => 'html',
                'label' => AmosNews::t('amosnews', 'Tipologia content type'),
                'value' => function ($model) {
                    return $model->newsContentType->name;
                },
                'visible' => $newsModule->enableAgid,
            ],
            'updated_by_agid' => [
                'attribute' => 'updated_by',
                'label' => AmosNews::t('amosnews', 'Ultima modifica'),
                'value' => function ($model) {
                    if ($user_profile = $model->getUserProfileByUserId($model->updated_by)) {
                        return $user_profile->nome . " " . $user_profile->cognome;
                    }
                    return '';
                },
                'format' => 'html',
                'visible' => !$newsModule->enableAgid,
            ],
            'updated_at_agid' => [
                'attribute' => 'updated_at',
                'label' => AmosNews::t('amosnews', 'Data ultima modifica'),
                'visible' => !$newsModule->enableAgid,
                'value' => function ($model) {
                    return \Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i');
                },
                'format' => 'raw',
            ],
            'updated_by' => [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    if ($user_profile = $model->getUserProfileByUserId($model->updated_by)) {
                        return $user_profile->nome . " " . $user_profile->cognome;
                    }
                    return '';
                },
                'format' => 'html',
                'visible' => $newsModule->enableAgid,
            ],
            'updated_at' => [
                'attribute' => 'updated_at',
                'visible' => $newsModule->enableAgid,
            ],
            'data_pubblicazione' => [
                'label' => $hidePubblicationDate ? AmosNews::t('amosnews', 'Pubblicato il') : AmosNews::t('amosnews', 'Inizio pubblicazione'),
                'attribute' => 'data_pubblicazione',
                'value' => function ($model) {
                    /** @var News $model */
                    return (is_null($model->data_pubblicazione)) ? AmosNews::t('amosnews', 'Immediata') : Yii::$app->formatter->asDatetime($model->data_pubblicazione, 'php:d/m/Y H:i');
                },
                'format' => 'raw',
                'visible' => !$newsModule->enableAgid,
            ],
            'data_rimozione' => [
                'label' => AmosNews::t('amosnews', 'Fine pubblicazione'),
                'visible' => (!$hidePubblicationDate && !$hideDataRimozioneView && !$newsModule->enableAgid),
                'attribute' => 'data_rimozione',
                'value' => function ($model) {
                    /** @var News $model */
                    return (is_null($model->data_rimozione) || $model->data_rimozione == '9999-12-31 00:00:00') ? AmosNews::t('amosnews', 'Mai') : Yii::$app->formatter->asDatetime($model->data_rimozione, 'php:d/m/Y H:i');
                },
                'format' => 'raw',
            ],
            'news_categorie_id' => [
                'attribute' => 'newsCategorie.titolo',
                'label' => AmosNews::t('amosnews', 'Categoria'),
                'visible' => $newsModule->showCategory,
            ],
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionExtractNews() {
        $basePath = realpath(\Yii::getAlias('@vendor/../common/uploads/temp'));
        $filename = "news_export_" . date('Y_m_d_His') . ".xlsx";
        $outputPath = $basePath . "/" . $filename;
        $queryParams = \Yii::$app->request->queryParams;
        $params = [];

        // Redirect url
        if (!empty($queryParams['redirectUrl'])) {
            $redirectUrl = $queryParams['redirectUrl'];
        } else {
            $redirectUrl = '/taskmanager';
        }
        // Query
        if (!empty($queryParams['query'])) {
            $params['query'] = $queryParams['query'];
        }

        /** @var \open20\amos\taskmanager\TaskManager $moduleTaskManager */
        $moduleTaskManager = \Yii::$app->getModule('taskmanager');

        $moduleTaskManager->scheduleJob(
                "news/export/export-news",
                'News',
                $outputPath,
                $params
        );

        return $this->redirect([$redirectUrl]);
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    protected function showCloneButton() {
        if ($this->module->enableCloningIntoCommunities && !empty($this->scope) && !empty($this->scope['community'])) {
            if (CommunityUtil::isLoggedCommunityManager()) {
                $this->view->params['labelSecondAction'] = AmosNews::t('amosnews', 'Dalla piattaforma');
                $this->view->params['titleSecondAction'] = AmosNews::t('amosnews', 'Importa una News dalla piattaforma');
                $this->view->params['urlSecondAction'] = ['/news/news/clone-news', 'communityId' => $this->scope['community']];
                $this->view->params['hideSecondAction'] = false;
            }
        }
    }

    /**
     * @return void
     */
    protected function setupMapView() {
        $this->newsModule->params['searchParams'][$this->id]['enable'] = false;
        $this->newsModule->params['orderParams'][$this->id]['enable'] = false;
        $this->newsModule->params['mapParams'][$this->id]['enable'] = false;
        $this->view->params['subTitleSection'] = AmosNews::t('amosnews', 'Notizie con punto di interesse');
        $this->filterDataProviderMaps($this->getDataProvider(), Yii::$app->request->get());
    }

    /**
     * Filter dataProvider to show only news with place_id not null
     * @param ActiveDataProvider $dataProvider
     * @return void
     */
    protected function filterDataProviderMaps($dataProvider, $getParams) {
        $dataProvider->query->andWhere(['is not', 'place_id', null])->andWhere(['!=', 'place_id', '']);
        $searchParams = $getParams[StringHelper::basename(get_class($this->modelSearch))];
        if (empty($searchParams['period'])) {
            $modelSearch = $this->modelSearch;
            $from = $modelSearch::getMapSearchFromTime(News::DEFAULT_PERIOD_MAP_SEARCH);
            $fromDate = date('Y-m-d', $from);
            $dataProvider->query->andWhere(['>=', 'data_pubblicazione', $fromDate]);
        }
    }

}
