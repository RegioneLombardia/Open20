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
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\Module;
use open20\amos\partnershipprofiles\rules\ReadAllExprOfIntRule;
use open20\amos\partnershipprofiles\utility\ExpressionsOfInterestUtility;
use open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use open20\amos\core\helpers\Html;

/**
 * Class ExpressionsOfInterestController
 * This is the class for controller "ExpressionsOfInterestController".
 * @package open20\amos\partnershipprofiles\controllers
 */
class ExpressionsOfInterestController extends \open20\amos\partnershipprofiles\controllers\base\ExpressionsOfInterestController
{
    /**
     * @var string $actionByUrl
     */
    protected $actionByUrl = '';

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
                                'all',
                                'all-admin',
                                'received',
                                'created-by',
                                'facilitator-expressions-of-interest',
                                'validate',
                                'reject'
                            ],
                            'roles' => ['EXPRESSIONS_OF_INTEREST_ADMINISTRATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'all',
                                'received',
                            ],
                            'roles' => ['PARTNERSHIP_PROFILES_READER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'all',
                                'created-by',
                            ],
                            'roles' => ['EXPRESSIONS_OF_INTEREST_CREATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'facilitator-expressions-of-interest',
                                'validate',
                                'reject'
                            ],
                            'roles' => ['PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR']
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
     * @inheritdoc
     */
    protected function setCustomModule()
    {
        parent::setCustomModule();

        if ($this->actionByUrl == 'facilitator-expressions-of-interest') {
            $this->customModule = Module::PARTNERPROFEXPROFINT;
        }
    }

    /**
     * @param string|null $layout
     * @param int|null $partnership_profile_id
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex($layout = null)
    {
        throw new ForbiddenHttpException('');
    }

    /**
     * Base operations for list views
     * @param string $pageTitle
     * @param ActiveDataProvider $dataProvider
     * @param bool $setCurrentDashboard
     * @param int|null $partnership_profile_id
     * @return string
     */
    private function baseListsAction($pageTitle, $setCurrentDashboard = true, $dataProvider,
                                     $partnership_profile_id = null, $child_of = null)
    {
        Url::remember();
        if ($partnership_profile_id) {
            $dataProvider->query->andWhere(['partnership_profile_id' => $partnership_profile_id]);
        }
        $this->setDataProvider($dataProvider);
        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams($setCurrentDashboard, $child_of);
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
     * @param int|null $partnership_profile_id
     * @return string
     */
    public function actionAllAdmin($partnership_profile_id = null)
    {
        Url::remember();
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $this->modelSearch->searchAllAdmin(Yii::$app->request->getQueryParams());
        return $this->baseListsAction('Amministra manifestazioni di interesse', false, $dataProvider, $partnership_profile_id);
    }

    /**
     * @param int|null $partnership_profile_id
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionAll($partnership_profile_id = null)
    {
        Yii::$app->view->params['textHelp'] = ['filename' => 'all_expressions_of_interest_help'];
        Url::remember();

        /** @var PartnershipProfiles $partnershipProfilesModel */
        $partnershipProfilesModel = $this->partnerProfModule->createModel('PartnershipProfiles');

        // START Check permission
        $partnershipProfile = $partnershipProfilesModel::findOne($partnership_profile_id);
        if (empty($partnershipProfile)) {
            throw new InvalidParamException();
        }
        if (!(\Yii::$app->user->can(ReadAllExprOfIntRule::className(), ['model' => $partnershipProfile]))) {
            throw new ForbiddenHttpException();
        }
        // END Check permission

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $this->modelSearch->searchAll(Yii::$app->request->getQueryParams());
        if (($partnershipProfile->created_by == Yii::$app->user->id) || ($partnershipProfile->partnership_profile_facilitator_id
            == Yii::$app->user->id)) {
            $dataProvider = $this->modelSearch->searchAll(Yii::$app->request->getQueryParams());
        } elseif (ExpressionsOfInterestUtility::isPartProfExprsOfIntCreator($partnershipProfile)) {
            $dataProvider = $this->modelSearch->searchAllAuthor(Yii::$app->request->getQueryParams());
        } elseif (ExpressionsOfInterestUtility::isPartProfExprsOfIntCreatorFacilitator($partnershipProfile)) {
            $dataProvider = $this->modelSearch->searchAllAuthorFacilitator(Yii::$app->request->getQueryParams());
        }
        return $this->baseListsAction('All', false, $dataProvider, $partnership_profile_id);
    }

    /**
     * @param int|null $partnership_profile_id
     * @return string
     */
    public function actionReceived($partnership_profile_id = null)
    {
        Url::remember();
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider                                   = $this->modelSearch->searchReceived(Yii::$app->request->getQueryParams());
        Yii::$app->view->params['textHelp']['filename'] = 'yours_interest_created_challenges-helper';
        return $this->baseListsAction(Module::t('amospartnershipprofiles', 'Manifestazioni di interesse ricevute'), true, $dataProvider, $partnership_profile_id);
    }

    /**
     * @param int|null $partnership_profile_id
     * @return string
     */
    public function actionCreatedBy($partnership_profile_id = null)
    {
        Url::remember();
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider                                   = $this->modelSearch->searchCreatedBy(Yii::$app->request->getQueryParams());
        Yii::$app->view->params['textHelp']['filename'] = 'yours_interest_created_challenges-helper';

        $this->setAvailableViews([
            'grid' => $this->viewGrid
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        if (!\Yii::$app->user->isGuest) {
            $this->view->params['titleSection'] = Module::t('amospartnershipprofiles', 'Create da me');
            $this->view->params['labelLinkAll'] = Module::t('amospartnershipprofiles', 'Manifestazioni di interesse ricevute');
            $this->view->params['urlLinkAll']   = Module::t('amospartnershipprofiles',
                    '/partnershipprofiles/expressions-of-interest/received');
            $this->view->params['titleLinkAll'] = Module::t('amospartnershipprofiles',
                    'Visualizza la lista delle manifestazioni di interesse ricevute'
            );
        }

        return $this->baseListsAction(Module::t('amospartnershipprofiles', 'Create da me'), true, $dataProvider, $partnership_profile_id);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionFacilitatorExpressionsOfInterest()
    {
        $dataProvider = $this->modelSearch->searchForFacilitator(Yii::$app->request->getQueryParams());
        return $this->baseListsAction(Module::t('amospartnershipprofiles', 'Manifestazioni di interesse dei miei utenti'), true, $dataProvider, null,
                WidgetIconPartnerProfExprOfIntDashboard::className());
    }

    /**
     * @param int $id Expression of Interest id.
     * @return \yii\web\Response
     */
    public function actionValidate($id)
    {
        /** @var ExpressionsOfInterest $expressionsOfInterestModel */
        $expressionsOfInterestModel = $this->partnerProfModule->createModel('ExpressionsOfInterest');
        $expressionofint            = $expressionsOfInterestModel::findOne($id);
        try {
            $expressionofint->sendToStatus(ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_ACTIVE);
            $ok = $expressionofint->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success',
                    Module::t('amospartnershipprofiles', '#expressionofinterestvalidated'));
            } else {
                Yii::$app->session->addFlash('danger',
                    Module::t('amospartnershipprofiles', '#ERROR_WHILE_VALIDATING_EXPRESSION'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id Expression of Interest id.
     * @return \yii\web\Response
     */
    public function actionReject($id)
    {
        /** @var ExpressionsOfInterest $expressionsOfInterestModel */
        $expressionsOfInterestModel = $this->partnerProfModule->createModel('ExpressionsOfInterest');
        $expressionofint            = $expressionsOfInterestModel::findOne($id);
        try {
            $expressionofint->sendToStatus(ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_REJECTED);
            $ok = $expressionofint->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success',
                    Module::t('amospartnershipprofiles', '#expressionofinterestrejected'));
            } else {
                Yii::$app->session->addFlash('danger',
                    Module::t('amospartnershipprofiles', '#ERROR_WHILE_VALIDATING_EXPRESSION'));
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
        $links = [];

        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestCreatedBy::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles',
                    'Visualizza le mie manifestazioni di interesse'),
                'label' => Module::t('amospartnershipprofiles', 'Le mie manifestazioni di interesse'),
                'url' => '/partnershipprofiles/expressions-of-interest/created-by'
            ];
        }
        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntExprOfInt::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles',
                    'Visualizza le manifestazione di interesse dei tuoi utenti'),
                'label' => Module::t('amospartnershipprofiles', 'Manifestazioni dei miei utenti'),
                'url' => '/partnershipprofiles/expressions-of-interest/facilitator-expressions-of-interest'
            ];
        }
        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestReceived::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Visualizza le manifestazione di interesse riveute'),
                'label' => Module::t('amospartnershipprofiles', 'Ricevute'),
                'url' => '/partnershipprofiles/expressions-of-interest/received'
            ];
        }

        if (\Yii::$app->user->can(\open20\amos\partnershipprofiles\widgets\icons\WidgetIconExpressionsOfInterestAllAdmin::class)) {
            $links[] = [
                'title' => Module::t('amospartnershipprofiles', 'Amministra le manifestazione di interesse'),
                'label' => Module::t('amospartnershipprofiles', 'Amministra'),
                'url' => '/partnershipprofiles/expressions-of-interest/all-admin'
            ];
        }

        return $links;
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amospartnershipprofiles', 'Manifestazioni di interesse');
            $urlLinkAll   = '';

            $ctaLoginRegister = Html::a(
                    Module::t('amospartnershipprofiles', '#beforeActionCtaLoginRegister'),
                    isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                        : \Yii::$app->params['platform']['backendUrl'].'/'.AmosAdmin::getModuleName().'/security/login',
                    [
                    'title' => Module::t('amospartnershipprofiles',
                        'Clicca per accedere o registrarti alla piattaforma {platformName}',
                        ['platformName' => \Yii::$app->name]
                    )
                    ]
            );
            $subTitleSection  = Html::tag(
                    'p',
                    Module::t('amospartnershipprofiles', '#beforeActionSubtitleSectionGuest',
                        ['ctaLoginRegister' => $ctaLoginRegister]
                    )
            );
        } else {
            $titleSection = Module::t('amospartnershipprofiles', 'Amministra le manifestazioni');
            $labelLinkAll = Module::t('amospartnershipprofiles', 'Create da me');
            $urlLinkAll   = Module::t('amospartnershipprofiles',
                    '/partnershipprofiles/expressions-of-interest/created-by');
            $titleLinkAll = Module::t('amospartnershipprofiles', 'Visualizza la lista delle manifestazioni di interesse create da me');

            $subTitleSection = ''; //Html::tag('p', Module::t('amospartnershipprofiles', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = '';//Module::t('amospartnershipprofiles', 'Nuova');
        $titleCreate = '';//Module::t('amospartnershipprofiles', 'Crea una nuova manifestazione');
        $labelManage = Module::t('amospartnershipprofiles', 'Gestisci');
        $titleManage = Module::t('amospartnershipprofiles', 'Gestisci le manifestazioni di interesse');
        $urlCreate   = '';//Module::t('amospartnershipprofiles', '/partnershipprofiles/partnership-profiles/create');
        $urlManage   = Module::t('amospartnershipprofiles', '#');

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
            'hideCreate' => true,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }
}