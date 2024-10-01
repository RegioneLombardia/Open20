<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\controllers
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\interfaces\OrganizationsModuleInterface;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\user\User;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\partnershipprofiles\assets\PartnershipProfilesAsset;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\utility\PartnershipProfilesUtility;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use open20\amos\core\helpers\Html;

/**
 * Class PartnershipProfilesController
 * This is the class for controller "PartnershipProfilesController".
 * @package open20\amos\partnershipprofiles\controllers
 */
class PartnershipProfilesController extends \open20\amos\partnershipprofiles\controllers\base\PartnershipProfilesController
{

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;
    /**
     * @var string $actionByUrl
     */
    protected $actionByUrl = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        PartnershipProfilesAsset::register(Yii::$app->view);

        $this->setActionFromUrl();

        parent::init();

        $this->setStartObjClassName($this->partnerProfModule->model('PartnershipProfiles'));
        $this->setTargetObjClassName(UserProfile::className());
        $this->setRedirectAction('update');
        $this->on(M2MEventsEnum::EVENT_BEFORE_ASSOCIATE_ONE2MANY, [$this, 'beforeAssociateOneToMany']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_RENDER_ASSOCIATE_ONE2MANY, [$this, 'beforeRenderOneToMany']);
    }

    /**
     * This method save the actual action id in a class variable using the pathInfo.
     */
    protected function setActionFromUrl()
    {
        $splittedPathInfo = explode('/', Yii::$app->request->pathInfo);
        $this->actionByUrl = end($splittedPathInfo);
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amospartnershipprofiles', 'Proposte di collaborazione');
            $urlLinkAll = '';

            $ctaLoginRegister = Html::a(
                Module::t('amospartnershipprofiles', '#beforeActionCtaLoginRegister'),
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => Module::t('amospartnershipprofiles',
                        'Clicca per accedere o registrarti alla piattaforma {platformName}',
                        ['platformName' => \Yii::$app->name]
                    )
                ]
            );
            $subTitleSection = Html::tag(
                'p',
                Module::t('amospartnershipprofiles', '#beforeActionSubtitleSectionGuest',
                    ['ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = Module::t('amospartnershipprofiles', 'Tutte le proposte');
            $labelLinkAll = Module::t('amospartnershipprofiles', 'Proposte di mio interesse');
            $urlLinkAll = Module::t('amospartnershipprofiles',
                '/partnershipprofiles/partnership-profiles/own-interest');
            $titleLinkAll = Module::t('amospartnershipprofiles', 'Visualizza la lista delle proposte di mio interesse');

            $subTitleSection = ''; //Html::tag('p', Module::t('amospartnershipprofiles', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = Module::t('amospartnershipprofiles', 'Nuova');
        $titleCreate = Module::t('amospartnershipprofiles', 'Crea una nuova proposta');
        $labelManage = Module::t('amospartnershipprofiles', 'Gestisci');
        $titleManage = Module::t('amospartnershipprofiles', 'Gestisci le proposte');
        $urlCreate = Module::t('amospartnershipprofiles', '/partnershipprofiles/partnership-profiles/create');
        $urlManage = Module::t('amospartnershipprofiles', '#');

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'partnershipprofiles',
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

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * This method checks if must be set the custom module.
     */
    protected function setCustomModule()
    {
        if ($this->actionByUrl == 'facilitator-partnership-profiles') {
            $this->customModule = Module::PARTNERPROFEXPROFINT;
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'all-admin',
                                'own-interest',
                                'archived',
                                'closed',
                                'created-by',
                                'to-validate',
                                'associate-facilitator',
                                'annulla-m2m',
                                'create-project-group',
                                'facilitator-partnership-profiles',
                                'validate',
                                'reject'
                            ],
                            'roles' => ['PARTNERSHIP_PROFILES_ADMINISTRATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'own-interest',
                                'archived',
                                'closed',
                            ],
                            'roles' => ['PARTNERSHIP_PROFILES_READER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'created-by',
                                'associate-facilitator',
                                'annulla-m2m',
                                'create-project-group',
                            ],
                            'roles' => ['PARTNERSHIP_PROFILES_CREATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'to-validate',
                                'associate-facilitator',
                                'annulla-m2m',
                                'create-project-group',
                                'validate',
                                'reject'
                            ],
                            'roles' => ['PARTNERSHIP_PROFILES_VALIDATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'facilitator-partnership-profiles',
                                'validate',
                                'associate-facilitator',
                                'reject'
                            ],
                            'roles' => ['PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'calculate-expiry-date',
                            ],
                            'roles' => ['@']
                        ],
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]);
        return $behaviors;
    }

    /**
     * @return string
     */
    public function actionCalculateExpiryDate()
    {
        $retval = [];
        if (Yii::$app->getRequest()->getIsAjax()) {
            $post = Yii::$app->getRequest()->post();
            $partnershipProfileDate = isset($post['partnershipProfileDate']) ? $post['partnershipProfileDate'] : null;
            $expirationInMonths = isset($post['expirationInMonths']) ? $post['expirationInMonths'] : null;
            if ($partnershipProfileDate && $expirationInMonths) {
                $dbDateFormat = 'Y-m-d';
                $date = \DateTime::createFromFormat($dbDateFormat, $partnershipProfileDate);
                if (!is_null($date) && !is_null($expirationInMonths) && is_numeric($expirationInMonths)) {
                    $interval = 'P' . $expirationInMonths . 'M';
                    $date->add(new \DateInterval($interval));
                    $retValDate = $date->format($dbDateFormat);
                    try {
                        $retval['dateTimeToView'] = Yii::$app->formatter->asDate($retValDate);
                    } catch (InvalidConfigException $exception) {
                        $retval = [];
                    }
                }
            }
        }
        return json_encode($retval);
    }

    protected function baseListsAction($pageTitle, $currentView = null, $setCurrentDashboard = true,
                                       $hideCreateNewBtn = false, $child_of = null)
    {
        Url::remember();
        if (empty($currentView)) {
            $currentView = 'list';
        }

        Yii::$app->view->params['textHelp']['filename'] = 'partnership_dashboard_description';

        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams($setCurrentDashboard, $hideCreateNewBtn, $child_of);
        $this->setCurrentView($this->getAvailableView($currentView));
        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->modelSearch,
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
            ]);
    }

    /**
     * Lists all PartnershipProfiles models for ADMIN users.
     * @param string|null $currentView
     * @return string
     */
    public function actionAllAdmin($currentView = null)
    {

        $this->setDataProvider($this->modelSearch->searchAllAdmin(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction('All partnership profiles', $currentView, false);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionOwnInterest($currentView = null)
    {
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));

        Url::remember();
        if (empty($currentView)) {
            $currentView = 'list';
        }

        Yii::$app->view->params['textHelp']['filename'] = 'partnership_dashboard_description';

        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams($setCurrentDashboard, $hideCreateNewBtn, $child_of);
        $this->setCurrentView($this->getAvailableView($currentView));


        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = Module::t('amospartnershipprofiles', 'Proposte di mio interesse');
            $this->view->params['labelLinkAll'] = Module::t('amospartnershipprofiles', 'Tutte le proposte');
            $this->view->params['urlLinkAll'] = Module::t('amospartnershipprofiles',
                '/partnershipprofiles/partnership-profiles/index');
            $this->view->params['titleLinkAll'] = Module::t('amospartnershipprofiles',
                'Visualizza la lista delle proposte di mio interesse'
            );
        }

        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->modelSearch,
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
            ]);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionCreatedBy($currentView = null)
    {
        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = Module::t('amospartnershipprofiles', 'Proposte di collaborazione');
        }
        $this->setDataProvider($this->modelSearch->searchCreatedBy(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction('Created By Me', $currentView);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionArchived($currentView = null)
    {
        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = Module::t('amospartnershipprofiles', 'Proposte di collaborazione archiviate');
        }
        $this->setDataProvider($this->modelSearch->searchArchived(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction('Archived', $currentView);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionClosed($currentView = null)
    {
        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = Module::t('amospartnershipprofiles', 'Proposte di collaborazione chiuse');
        }
        $this->setDataProvider($this->modelSearch->searchClosed(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction('Closed', $currentView);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionFacilitatorPartnershipProfiles($currentView = null)
    {
        $this->setDataProvider($this->modelSearch->searchForFacilitator(Yii::$app->request->getQueryParams()));
        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = Module::t('amospartnershipprofiles', 'Proposte dei miei utenti');
        }
        return $this->baseListsAction('Proposte dei miei utenti', $currentView, true, true,
            WidgetIconPartnerProfExprOfIntDashboard::className());
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionToValidate($currentView = null)
    {
        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = Module::t('amospartnershipprofiles', 'Proposte da validare');
        }
        $this->setDataProvider($this->modelSearch->searchToValidate(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction('To Validate', $currentView);
    }

    /**
     * @param int $id
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionAssociateFacilitator($id)
    {
        /** @var PartnershipProfiles $partnershipProfile */
        $partnershipProfile = $this->findModel($id);
        if (
            (Yii::$app->user->id != $partnershipProfile->created_by) &&
            !Yii::$app->user->can('ADMIN') &&
            !Yii::$app->user->can('PARTNERSHIP_PROFILES_ADMINISTRATOR') &&
            !Yii::$app->user->can('PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR') &&
            !Yii::$app->user->can('PARTNERSHIP_PROFILES_VALIDATOR')
        ) {
            throw new ForbiddenHttpException(Yii::t('amoscore', 'Non sei autorizzato a visualizzare questa pagina'));
        }

        $this->setUpLayout('main');
        $this->setMmTargetKey('facilitatoreUserProfileId');
        $this->setTargetUrl('associate-facilitator');
        return $this->actionAssociateOneToMany($id);
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeAssociateOneToMany($event)
    {
        $this->setUpLayout('main');
    }

    /**
     * @param \yii\base\Event $event
     */
    public function beforeRenderOneToMany($event)
    {
        Yii::$app->view->params['model'] = $this->model;
    }

    /**
     * @param PartnershipProfiles $model
     * @return array
     */
    public function getFacilitatorsList($model)
    {
        $cwhConfigContents = CwhConfigContents::findOne(['tablename' => PartnershipProfiles::tableName()]);
        $pubblicazione = CwhPubblicazioni::findOne(['content_id' => $model->id, 'cwh_config_contents_id' => $cwhConfigContents->id]);
        $cwhPubblicazioniCwhNodiValidatoriMms = $pubblicazione->cwhPubblicazioniCwhNodiValidatoriMms;
        $userIds = [];

        foreach ($cwhPubblicazioniCwhNodiValidatoriMms as $cwhPubblicazioniCwhNodiValidatoriMm) {
            /** @var \open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm $cwhPubblicazioniCwhNodiValidatoriMm */
            $cwhConfig = $cwhPubblicazioniCwhNodiValidatoriMm->cwhConfig;

            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule(AmosAdmin::getModuleName());
            $organizationsModuleName = $adminModule->getOrganizationModuleName();
            $organizzazioniModule = \Yii::$app->getModule($organizationsModuleName);

            $classNames = [
                "common\models\User",
                User::className()
            ];
            if (!is_null($organizzazioniModule)) {
                /** @var OrganizationsModuleInterface $organizzazioniModule */
                $classNames[] = $organizzazioniModule->getOrganizationModelClass();
            }

            $communityModule = \Yii::$app->getModule('community');

            if (in_array($cwhConfig->classname, $classNames)) {
                $pluginFacilitators = \Yii::$app->getAuthManager()->getUserIdsByRole('PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR');
                $allPlatformFacilitatorIds = UserProfileUtility::getAllFacilitatorUserIds();
                $userIds = ArrayHelper::merge($userIds, $allPlatformFacilitatorIds,
                    $pluginFacilitators);
            } elseif (!is_null($communityModule)) {
                /** @var \open20\amos\community\AmosCommunity $communityModule */
                if ($cwhConfig->classname == \open20\amos\community\models\Community::className()) {
                    $community = \open20\amos\community\models\Community::findOne($cwhPubblicazioniCwhNodiValidatoriMm->cwh_network_id);
                    if (!is_null($community)) {
                        $communityManagers = $community->communityManagers;
                        foreach ($communityManagers as $communityManager) {
                            /** @var User $communityManager */
                            $userIds[] = $communityManager->id;
                        }
                    }
                }
            }
        }

        $userIds = array_unique($userIds);

        return $userIds;
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCreateProjectGroup($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        if (!($this->viewCreateProjectGroupBtn($this->model))) {
            throw new ForbiddenHttpException();
        }

        Yii::$app->view->params['textHelp'] = ['filename' => 'create_project_group_intro'];

        $users = [];
        $expressionsOfInterest = $this->model->expressionsOfInterest;
        foreach ($expressionsOfInterest as $expressionOfInterest) {
            if ($expressionOfInterest->status == ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT) {
                /** @var UserProfile $userProfile */
                $userProfile = $expressionOfInterest->createdUserProfile;
                $users[] = $userProfile->user;
            }
        }
        $dataProvider = new ArrayDataProvider(['allModels' => $users]);

        if (Yii::$app->request->post('selectedUsers')) {
            $ok = PartnershipProfilesUtility::createProjectGroupCommunity($this->model,
                Yii::$app->request->post('selectedUsers'));
            if ($ok) {
                Yii::$app->getSession()->addFlash('success',
                    Module::tHtml('amospartnershipprofiles', 'Project group successfully created'));
                return $this->redirect(['view', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    Module::tHtml('amospartnershipprofiles', 'Error while creating project group'));
            }
        }

        return $this->render('create_project_group', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param PartnershipProfiles $model
     * @return bool
     */
    public function viewCreateProjectGroupBtn($model)
    {
        return (
            (Yii::$app->user->id == $model->created_by) &&
            !$model->community_id &&
            ($model->status = PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED) &&
            (count($model->relevantExpressionsOfInterest) > 0)
        );
    }

    /**
     * @param PartnershipProfiles $model
     * @return bool
     */
    public function viewAccessProjectGroupBtn($model)
    {
        return (
            !is_null($model->community_id) && !empty($model->community) &&
            $model->community->isNetworkUser($model->community_id, Yii::$app->user->id)
        );
    }

    /**
     * @param int $id Document id.
     * @return \yii\web\Response
     */
    public function actionValidate($id)
    {
        /** @var PartnershipProfiles $partnershipProfilesModel */
        $partnershipProfilesModel = $this->partnerProfModule->createModel('PartnershipProfiles');
        $partnership = $partnershipProfilesModel::findOne($id);
        try {
            $partnership->sendToStatus(PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED);
            $ok = $partnership->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success',
                    Module::t('amospartnershipprofiles', '#partnershipprofilevalidated'));
            } else {
                Yii::$app->session->addFlash('danger', Module::t('amospartnershipprofiles', '#ERROR_WHILE_VALIDATING'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id Document id.
     * @return \yii\web\Response
     */
    public function actionReject($id)
    {
        /** @var PartnershipProfiles $partnershipProfilesModel */
        $partnershipProfilesModel = $this->partnerProfModule->createModel('PartnershipProfiles');
        $partnership = $partnershipProfilesModel::findOne($id);
        try {
            $partnership->sendToStatus(PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT);
            $ok = $partnership->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success',
                    Module::t('amospartnershipprofiles', '#partnershipprofilerejected'));
            } else {
                Yii::$app->session->addFlash('danger', Module::t('amospartnershipprofiles', '#ERROR_WHILE_REJECTING'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     *
     * @return array
     */
    public static function getManageLinks()
    {
        $module = \Yii::$app->getModule('partnershipprofiles');
        if (get_class(Yii::$app->controller) != 'open20\amos\partnershipprofiles\controllers\PartnershipProfilesController') {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Visualizza tutte le proposte di collaborazione'),
                'label' => Module::t('amospartnershipprofiles', 'Tutte'),
                'url' => '/partnershipprofiles/partnership-profiles/index'
            ];
        }

        $links[] = [
            'title' => Module::t('amospartnershipprofiles', 'Visualizza le proposte di collaborazione create'),
            'label' => Module::t('amospartnershipprofiles', 'Create da me'),
            'url' => '/partnershipprofiles/partnership-profiles/created-by'
        ];

        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntPartProf::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles',
                    'Visualizza le proposte di collaborazione dei miei utenti'),
                'label' => Module::t('amospartnershipprofiles', 'Dei miei utenti'),
                'url' => '/partnershipprofiles/partnership-profiles/facilitator-partnership-profiles'
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesToValidate::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Visualizza le proposte di collaborazione da validare'),
                'label' => Module::t('amospartnershipprofiles', 'Da validare'),
                'url' => '/partnershipprofiles/partnership-profiles/to-validate'
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesArchived::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Visualizza le proposte di collaborazione archiviate'),
                'label' => Module::t('amospartnershipprofiles', 'Archiviate'),
                'url' => '/partnershipprofiles/partnership-profiles/archived',
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesClosed::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Visualizza le proposte di collaborazione chiuse'),
                'label' => Module::t('amospartnershipprofiles', 'Chiuse'),
                'url' => '/partnershipprofiles/partnership-profiles/closed'
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnershipProfilesAllAdmin::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Amministra le proposte di collaborazione'),
                'label' => Module::t('amospartnershipprofiles', 'Amministra'),
                'url' => '/partnershipprofiles/partnership-profiles/all-admin'
            ];
        }

        if ($module->enableCategories && \Yii::$app->user->can('PARTNERSHIP_PROFILES_CATEGORY_ADMINISTRATOR')) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Gestisci le categorie'),
                'label' => Module::t('amospartnershipprofiles', 'Categorie'),
                'url' => '/partnershipprofiles/partnership-profiles-category/index'
            ];
        }

        return $links;
    }
}