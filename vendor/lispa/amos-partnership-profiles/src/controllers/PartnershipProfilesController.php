<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\partnershipprofiles\controllers
 * @category   CategoryName
 */

namespace lispa\amos\partnershipprofiles\controllers;

use lispa\amos\admin\AmosAdmin;
use lispa\amos\admin\interfaces\OrganizationsModuleInterface;
use lispa\amos\admin\models\UserProfile;
use lispa\amos\admin\utility\UserProfileUtility;
use lispa\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use lispa\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use lispa\amos\core\user\User;
use lispa\amos\cwh\models\CwhConfigContents;
use lispa\amos\cwh\models\CwhPubblicazioni;
use lispa\amos\partnershipprofiles\assets\PartnershipProfilesAsset;
use lispa\amos\partnershipprofiles\models\ExpressionsOfInterest;
use lispa\amos\partnershipprofiles\models\PartnershipProfiles;
use lispa\amos\partnershipprofiles\Module;
use lispa\amos\partnershipprofiles\utility\PartnershipProfilesUtility;
use lispa\amos\partnershipprofiles\widgets\icons\WidgetIconPartnerProfExprOfIntDashboard;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Class PartnershipProfilesController
 * This is the class for controller "PartnershipProfilesController".
 * @package lispa\amos\partnershipprofiles\controllers
 */
class PartnershipProfilesController extends \lispa\amos\partnershipprofiles\controllers\base\PartnershipProfilesController
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

        $this->setStartObjClassName(PartnershipProfiles::className());
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
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
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

    protected function baseListsAction($pageTitle, $currentView = null, $setCurrentDashboard = true, $hideCreateNewBtn = false, $child_of = null)
    {
        Url::remember();
        if (empty($currentView)) {
            $currentView = 'list';
        }

        Yii::$app->view->params['textHelp']['filename'] = 'partnership_dashboard_description';

        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams($setCurrentDashboard, $hideCreateNewBtn, $child_of);
        $this->setCurrentView($this->getAvailableView($currentView));
        return $this->render('index', [
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
        return $this->baseListsAction('Own Interest', $currentView);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionCreatedBy($currentView = null)
    {
        $this->setDataProvider($this->modelSearch->searchCreatedBy(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction('Created By Me', $currentView);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionArchived($currentView = null)
    {
        $this->setDataProvider($this->modelSearch->searchArchived(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction('Archived', $currentView);
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionClosed($currentView = null)
    {
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
        return $this->baseListsAction('Partnership profiles', $currentView, true, true, WidgetIconPartnerProfExprOfIntDashboard::className());
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionToValidate($currentView = null)
    {
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
            /** @var \lispa\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm $cwhPubblicazioniCwhNodiValidatoriMm */
            $cwhConfig = $cwhPubblicazioniCwhNodiValidatoriMm->cwhConfig;

            /** @var AmosAdmin $adminModule */
            $adminModule = \Yii::$app->getModule('admin');
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
                $userIds = ArrayHelper::merge($userIds, $allPlatformFacilitatorIds, $pluginFacilitators);
            } elseif (!is_null($communityModule)) {
                /** @var \lispa\amos\community\AmosCommunity $communityModule */
                if ($cwhConfig->classname == \lispa\amos\community\models\Community::className()) {
                    $community = \lispa\amos\community\models\Community::findOne($cwhPubblicazioniCwhNodiValidatoriMm->cwh_network_id);
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
            $ok = PartnershipProfilesUtility::createProjectGroupCommunity($this->model, Yii::$app->request->post('selectedUsers'));
            if ($ok) {
                Yii::$app->getSession()->addFlash('success', Module::tHtml('amospartnershipprofiles', 'Project group successfully created'));
                return $this->redirect(['view', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', Module::tHtml('amospartnershipprofiles', 'Error while creating project group'));
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
        $partnership = PartnershipProfiles::findOne($id);
        try {
            $partnership->sendToStatus(PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED);
            $ok = $partnership->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', Module::t('amospartnershipprofiles', '#partnershipprofilevalidated'));
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
        $partnership = PartnershipProfiles::findOne($id);
        try {
            $partnership->sendToStatus(PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT);
            $ok = $partnership->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', Module::t('amospartnershipprofiles', '#partnershipprofilerejected'));
            } else {
                Yii::$app->session->addFlash('danger', Module::t('amospartnershipprofiles', '#ERROR_WHILE_REJECTING'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }
}
