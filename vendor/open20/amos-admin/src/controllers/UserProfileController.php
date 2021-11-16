<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\admin\exceptions\AdminException;
use open20\amos\admin\interfaces\OrganizationsModuleInterface;
use open20\amos\admin\models\CambiaPasswordForm;
use open20\amos\admin\models\DropAccountForm;
use open20\amos\admin\models\search\UserProfileAreaSearch;
use open20\amos\admin\models\search\UserProfileRoleSearch;
use open20\amos\admin\models\UserOtpCode;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileReactivationRequest;
use open20\amos\admin\utility\UserProfileMailUtility;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\core\utilities\ArrayUtility;
use open20\amos\core\utilities\Email;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\socialauth\models\SocialAuthServices;
use openinnovation\organizations\models\base\Organizations;
use raoul2000\workflow\base\WorkflowException;
use open20\amos\admin\models\UserProfileExternalFacilitator;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class UserProfileController
 * @package open20\amos\admin\controllers
 */
class UserProfileController extends \open20\amos\admin\controllers\base\UserProfileController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        ModuleAdminAsset::register(Yii::$app->view);

        $this->setStartObjClassName(AmosAdmin::instance()->model('UserProfile'));
        $this->setTargetObjClassName(AmosAdmin::instance()->model('UserProfile'));
        $this->setRedirectAction('update');
        $this->on(M2MEventsEnum::EVENT_BEFORE_ASSOCIATE_ONE2MANY, [$this, 'beforeAssociateOneToMany']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_RENDER_ASSOCIATE_ONE2MANY, [$this, 'beforeRenderOneToMany']);
        $this->on(M2MEventsEnum::EVENT_AFTER_ASSOCIATE_ONE2MANY, [$this, 'afterAssociateOneToMany']);


        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setUpLayout('list');
        } else {
            $this->setUpLayout();
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $result = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className(),
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'password-expired',
                                'enable-google-service',
                                'disable-google-service',
                                'get-social-service-status',
                                'get-social-user',
                                'complete-profile',
                                'send-request-external-facilitator',
                                'connect-spid',
                                'drop-account-by-email',
                                'drop-account',
                                'modify-email',
                                'find-name'
                            ],
                            'roles' => ['BASIC_USER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'accept-request',
                                'reject-request'
                            ],
                            'roles' => ['FACILITATOR_EXTERNAL', 'ADMIN']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'cambia-password'
                            ],
                            'roles' => ['CHANGE_USER_PASSWORD']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'associate-facilitator',
                                'associate-prevalent-partnership',
                                'update-profile',
                                'remove-prevalent-partnership',
                                'drop-account'
                            ],
                            'roles' => ['UpdateOwnUserProfile']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'drop-account',
                                'update-profile',
                                'inactive-users',
                                'validated-users',
                                'facilitator-users',
                                'community-manager-users',
                                'associate-facilitator',
                                'associate-prevalent-partnership',
                                'remove-prevalent-partnership',
                                'annulla-m2m',
                                'deactivate-account',
                                'reactivate-account',
                                'reject-reactivation-request',
                                'def-facilitator-present',
                                'validate-user-profile',
                                'reject-user-profile',
                                'contacts',
                                'password-expired',
                                'cambia-password',
                                'drop-account-by-email',
                                'modify-email'
                            ],
                            'roles' => ['ADMIN', 'AMMINISTRATORE_UTENTI']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'validate-user-profile',
                                'reject-user-profile',
                            ],
                            'roles' => ['FACILITATOR', 'VALIDATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'validated-users',
                                'facilitator-users',
                                'community-manager-users',
                                'annulla-m2m',
                                'deactivate-account',
                                'def-facilitator-present',
                                'contacts'
                            ],
                            'roles' => ['USER_PROFILE_BASIC_LIST_ACTIONS']
                        ],
                        [
                            'actions' => [
                                'spedisci-credenziali'
                            ],
                            'allow' => true,
                            'roles' => ['GESTIONE_UTENTI'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'remove-prevalent-partnership' => ['post'],
                        'delete' => ['post', 'get']
                    ]
                ]
            ]);

        return $result;
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
     * @param $event
     */
    public function afterAssociateOneToMany($event)
    {

        try {

            $userprofile_class = AmosAdmin::getInstance()->model('UserProfile');

            if (!empty($event->sender) && is_object($event->sender) && $event->sender instanceof $userprofile_class) {
                if (!empty($event->sender->prevalent_partnership_id)) {
                    $admin = AmosAdmin::getInstance();
                    /** @var  $organizationsModule OrganizationsModuleInterface */
                    $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
                    $organizationsModule->saveOrganizationUserMm(Yii::$app->user->id,
                        $event->sender->prevalent_partnership_id);
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * This method return all enabled professional roles translated.
     * @return array
     */
    public function getRoles()
    {
        return ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileRoleSearch::searchAll(), 'id', 'name'), 'amosadmin', AmosAdmin::className()
        );
    }

    /**
     * This method return all enabled professional areas translated.
     * @return array
     */
    public function getAreas()
    {
        return ArrayUtility::translateArrayValues(
            ArrayHelper::map(UserProfileAreaSearch::searchAll(), 'id', 'name'), 'amosadmin', AmosAdmin::className()
        );
    }

    public function actionFindName($name = "") {
        $users = UserProfile::find();
        $users->asArray();
        $users->limit(5);
        $users->select(new \yii\db\Expression('CONCAT(nome," ",cognome) as name, CONCAT("/'.AmosAdmin::getModuleName().'/user-profile/view?id=",id) as url, user_id as user_id'));
        //TODO fix this shit
        $users->andHaving(new \yii\db\Expression("name LIKE \"%{$name}%\""));

        return json_encode($users->all());
    }


    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosAdmin::t('amosadmin', 'Utenti');
            $urlLinkAll = '/amosadmin/user-profile/validated-users';


        } else {
            $titleSection = AmosAdmin::t('amosadmin', 'Utenti');

        }

        $labelCreate = AmosAdmin::t('amosadmin', 'Nuovo');
        $titleCreate = AmosAdmin::t('amosadmin', 'Add new user');
        $labelManage = AmosAdmin::t('amosadmin', 'Gestisci');
        $titleManage = AmosAdmin::t('amosadmin', 'Gestisci gli utenti');
        $urlCreate = '/amosadmin/user-profile/create';
        $urlManage = AmosAdmin::t('amosadmin', '#');

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'utenti',
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
     * @param int $id The user id
     * @return \yii\web\Response
     */
    public function actionValidateUserProfile($id)
    {
        $userProfile = UserProfile::findOne($id);
        try {
            $userProfile->updated_by = \Yii::$app->user->id;
            $userProfile->status = UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED;
            $ok = $userProfile->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', '#USER_VALIDATED'));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#ERROR_WHILE_VALIDATING'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id The user id
     * @return \yii\web\Response
     */
    public function actionRejectUserProfile($id)
    {
        $userProfile = UserProfile::findOne($id);
        try {
            $userProfile->updated_by = \Yii::$app->user->id;
            $userProfile->status = UserProfile::USERPROFILE_WORKFLOW_STATUS_NOTVALIDATED;
            $ok = $userProfile->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', '#USER_REJECTED'));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#ERROR_WHILE_REJECTING'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return string
     */
    public function actionUpdateProfile($id)
    {
        $model = $this->actionUpdate($id, false);

        return $this->render('update_profile',
            [
                'user' => $model->user,
                'model' => $model,
                'tipologiautente' => $model->tipo_utente,
                'permissionSave' => 'USERPROFILE_UPDATE',
            ]);
    }

    /**
     * @param int $id
     */
    public function actionSpedisciCredenziali($id)
    {
        $this->actionSendCredentials($id);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionSendCredentials($id)
    {
        /** @var \open20\amos\admin\models\UserProfile $model */
        $model = $this->findModel($id);
        if($this->adminModule->enableDlSemplification){
            throw new ForbiddenHttpException(AmosAdmin::t('amosadmin', "Forbidden"));
        }
        if ($model && $model->user && $model->user->email) {
            $sent = $this->sendPasswordResetMail($model);

            if ($sent) {
                Yii::$app->session->addFlash('success',
                    AmosAdmin::t('amosadmin', 'Credenziali spedite correttamente alla email {email}',
                        ['email' => $model->user->email]));
            } else {
                Yii::$app->session->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'Si è verificato un errore durante la spedizione delle credenziali'));
            }
        } else {
            Yii::$app->session->addFlash('danger',
                AmosAdmin::t('amosadmin',
                    'L\'utente non esiste o è sprovvisto di email, impossibile spedire le credenziali'));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param UserProfile $model
     * @return bool
     */
    public function sendCredentialsMail($model)
    {
        return UserProfileUtility::sendCredentialsMail($model);
    }

    /**
     * @param UserProfile $model
     * @return bool
     */
    public function sendPasswordResetMail($model)
    {
        return UserProfileUtility::sendPasswordResetMail($model);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCambiaPassword($id)
    {

        $this->setUpLayout('form');
        $dbuser = $this->findModel($id);
        $model = $this->adminModule->createModel('CambiaPasswordForm');
        if($this->adminModule->enableDlSemplification){
            throw new ForbiddenHttpException(AmosAdmin::t('amosadmin', "Forbidden"));
        }

        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $dbuser->user) {
                $password = $model->nuovaPassword;
                $dbuser->user->setPassword($password);
                if ($dbuser->user->validate() && $dbuser->user->save()) {
                    Yii::$app->getSession()->addFlash('success',
                        AmosAdmin::t('amosadmin', 'Password cambiata correttamente'));
                    return $this->redirect(Url::previous());
                } else {
                    Yii::$app->getSession()->addFlash('warning',
                        AmosAdmin::t('amosadmin', 'Cambio password non riuscito, controllare i dati e riprovare.'));
                    return $this->render('password', ['model' => $model, 'id' => $id]);
                }
            } else {
                Yii::$app->getSession()->addFlash('warning',
                    AmosAdmin::t('amosadmin', 'Cambio password non riuscito, controllare i dati e riprovare.'));
                return $this->render('password', ['model' => $model, 'id' => $id]);
            }
        } else {
            return $this->render('password', ['model' => $model, 'id' => $id]);
        }
    }

    /**
     * @return array|null
     */
    public function getWhiteListRoles()
    {
        $arrayRuoli = null;
        $moduleWhite = $this->module->getWhiteListRoles();

        foreach ($moduleWhite as $rule) {
            $arrayRuoli[] = Yii::$app->authManager->getRole($rule);
        }
        return $arrayRuoli;
    }

    /**
     * In Icon view if we are in a network dashboard eg. community, projects, ..
     * view additional information related to current scope
     * @param int $userId
     */
    public function setCwhScopeNewtworkInfo($userId)
    {
        $this->setCwhScopeNetworkInfo($userId);
    }

    /**
     * In Icon view if we are in a network dashboard eg. community, projects, ..
     * view additional information related to current scope
     * @param int $userId
     */
    public function setCwhScopeNetworkInfo($userId)
    {
        /** @var \open20\amos\cwh\AmosCwh $cwh */
        $cwh = Yii::$app->getModule("cwh");
        // if we are navigating users inside a sprecific entity (eg. a community)
        // see users filtered by entity-user association table
        if (isset($cwh)) {
            $cwh->setCwhScopeFromSession();
            if (!empty($cwh->userEntityRelationTable)) {
                \Yii::$app->view->params['cwhScope'] = true;
                $mmTable = $cwh->userEntityRelationTable['mm_name'];
                $entityField = $cwh->userEntityRelationTable['entity_id_field'];
                $entityId = $cwh->userEntityRelationTable['entity_id'];
                $entity = key($cwh->scope);
                $network = \open20\amos\cwh\models\CwhConfig::findOne(['tablename' => $entity]);
                if (!empty($network)) {
                    $networkObj = Yii::createObject($network->classname);
                    if ($networkObj->hasMethod('getMmClassName')) {
                        $userField = $networkObj->getMmUserIdFieldName();
                        $className = ($networkObj->getMmClassName());
                        $userEntityMm = $className::findOne([$entityField => $entityId, $userField => $userId]);
                        $networkModel = $networkObj->findOne($entityId);
                        if (!empty($networkModel) && !is_null($userEntityMm)) {
                            if ($userEntityMm->hasProperty('role')) {
                                $role = BaseAmosModule::t('amos' . $entity, $userEntityMm->role);
                                \Yii::$app->view->params['role'] = $role;
                            }
                            if ($userEntityMm->hasProperty('status')) {
                                $status = BaseAmosModule::t('amos' . $entity,
                                    $userEntityMm->status);
                                \Yii::$app->view->params['status'] = $status;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param UserProfile $model
     * @return array
     * @var \cornernote\workflow\manager\components\WorkflowDbSource $workflowSource
     *
     */
    public function getWorkflowStatuses($model)
    {
        $workflowSource = $model->getWorkflowSource();
        $allStatuses = $workflowSource->getAllStatuses(UserProfile::USERPROFILE_WORKFLOW);
        $userProfileWorkflowStatuses = [];

        foreach ($allStatuses as $status) {
            $userProfileWorkflowStatuses[$status->getId()] = AmosAdmin::t('amosadmin', $status->getLabel());
        }

        return $userProfileWorkflowStatuses;
    }

    /**
     * @return []
     * @var  OrganizationsModuleInterface $organizationsModule
     *
     */
    public function getAllOrganizations()
    {
        $admin = AmosAdmin::getInstance();

        $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
        $organizationsModels = [];

        if (!is_null($organizationsModule)) {
            $organizationsModels = $organizationsModule
                ->getOrganizationsListQuery()
                ->select('id, name')
                ->asArray()
                ->all();
        }

        return $organizationsModels;
    }

    /**
     * @return array
     */
    public function getAllOrganizationsForSelect()
    {
        $organizations = ArrayHelper::merge(
            ['-1' => AmosAdmin::t('amosadmin', 'No prevalent partnership')],
            ArrayHelper::map($this->getAllOrganizations(), 'id', 'name'));

        return $organizations;
    }

    /**
     * @return UserProfile[]
     * @var ActiveQuery $query
     *
     */
    public function getFacilitators()
    {
        $facilitatorUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole('FACILITATOR');

        $query = AmosAdmin::instance()
            ->createModel('UserProfile')
            ->find()
            ->select(["user_profile.id, nome, cognome, CONCAT(cognome, ' ', nome) AS surnameName", 'user_id'])
            ->joinWith(['user'])
            ->andWhere([UserProfile::tableName() . '.attivo' => 1])
            ->andWhere(['in', 'user_id', $facilitatorUserIds])
            ->andWhere(['!=', 'dont_show_facilitator', 1])
            ->orderBy(['cognome' => SORT_ASC, 'nome' => SORT_ASC]);

        return $query->asArray()->all();
    }

    /**
     * @return array
     */
    public function getFacilitatorsForSelect()
    {
        return ArrayHelper::merge(
            ['-1' => AmosAdmin::t('amosadmin', 'Not selected')],
            ArrayHelper::map($this->getFacilitators(), 'id', 'surnameName'));
    }

    /**
     * This method make the export columns array to set in the configurations of the DataProviderView widget.
     * @param UserProfile $model
     * @return array
     */
    public function getExportColumns($model)
    {
        return [
            'id',
            'nome',
            'cognome',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var \open20\amos\admin\models\UserProfile $model */
                    return $model->hasWorkflowStatus() ? AmosAdmin::t('amosadmin',
                        $model->getWorkflowStatus()->getLabel()) : '-';
                }
            ],
            'user.email' => [
                'attribute' => 'user.email',
                'label' => AmosAdmin::t('amosadmin', 'Email')
            ],
            'prevalentPartnership.name' => [
                'attribute' => 'prevalentPartnership.title',
                'label' => $model->getAttributeLabel('prevalentPartnership')
            ],
            'facilitatore.nomeCognome' => [
                'attribute' => 'facilitatore.nomeCognome',
                'label' => $model->getAttributeLabel('facilitatore')
            ],
            'notify_from_editorial_staff' => [
                'attribute' => 'notify_from_editorial_staff',
                'format' => 'boolean',
                'label' => $model->getAttributeLabel('notify_from_editorial_staff')
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    /** @var \open20\amos\admin\models\UserProfile $model */
                    if ($model->created_at) {
                        return Yii::$app->formatter->asDatetime($model->created_at);
                    } else {
                        return '';
                    }
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    /** @var \open20\amos\admin\models\UserProfile $model */
                    if ($model->updated_at) {
                        return Yii::$app->formatter->asDatetime($model->updated_at);
                    } else {
                        return '';
                    }
                }
            ]
        ];
    }

    public function actionAssociateFacilitator($id, $external = false)
    {
        $this->setUpLayout('main');
        if ($external) {
            $this->setMmTargetKey('external_facilitator_id');
        } else {
            $this->setMmTargetKey('facilitatore_id');
        }
        $this->setTargetUrl('associate-facilitator');
        $this->setTargetObjClassName(AmosAdmin::instance()->model('UserProfile'));
        return $this->actionAssociateOneToMany($id);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionAssociatePrevalentPartnership($id)
    {
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setUpLayout('mainFullsize');
        } else {
            $this->setUpLayout('main');
        }

        $this->setMmTargetKey('prevalent_partnership_id');
        $this->setTargetUrl('associate-prevalent-partnership');
        $admin = AmosAdmin::getInstance();
        /** @var  $organizationsModule OrganizationsModuleInterface */
        $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
        $this->setTargetObjClassName($organizationsModule->getOrganizationModelClass());
        return $this->actionAssociateOneToMany($id);
    }

    /**
     * Remove association with prevalent partnership profile
     * @param int|$id
     * @return mixed|null
     */
    public function actionRemovePrevalentPartnership($id)
    {
        if (Yii::$app->request->isPost && Yii::$app->request->isAjax) {
            $this->model = $this->findModel($id);
            $this->model->prevalent_partnership_id = null;
            $ok = $this->model->save(false);
            if (!$ok) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'Si &egrave; verificato un errore durante il salvataggio'));
            }
            return $this->redirect(['annulla-m2m', 'id' => $id]);
        }
        Yii::$app->session->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
        return null;
    }

    /**
     * Lists all active users that was validated at least once.
     * @return string
     */
    public function actionValidatedUsers()
    {
//        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
//            $this->setUpLayout('list');
//        } else {
//            $this->setUpLayout('list');
//        }

        Url::remember();
        $this->setUpLayout('list');

        $availableViews = [];
        if (!empty(Yii::$app->session['cwh-scope'])) {
            $defaultViews = [
                'icon' => $this->iconView
            ];
            $viewType = reset($this->adminModule->defaultListViews);
            if (isset($defaultViews[$viewType])) {
                $availableViews[$viewType] = $defaultViews[$viewType];
            }
        } else {
            $defaultViews = [
                'icon' => $this->iconView,
                'grid' => $this->gridView,
            ];
            foreach ($this->adminModule->defaultListViews as $view) {
                if (isset($defaultViews[$view])) {
                    $availableViews[$view] = $defaultViews[$view];
                }
            }
        }

        $this->setAvailableViews($availableViews);

        $this->setDataProvider($this->getModelSearch()->searchOnceValidatedUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Validated users'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'fromAction' => 'validated-users'
            ]
        );
    }

    /**
     * Lists all active users that are a community manager for at least one community.
     * @return string
     */
    public function actionCommunityManagerUsers()
    {
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setUpLayout('list');
        } else {
            $this->setUpLayout('list');
        }

        Url::remember();

        $this->setAvailableViews([
            'list' => $this->listView
        ]);
        $this->setCurrentView($this->getAvailableView('list'));
        $this->setDataProvider($this->getModelSearch()->searchCommunityManagerUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Community Managers'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'fromAction' => 'community-manager-users'
            ]);
    }

    /**
     * Lists all active users with "FACILITATOR" role.
     * @return string
     */
    public function actionFacilitatorUsers()
    {
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setUpLayout('list');
        } else {
            $this->setUpLayout('list');
        }

        Url::remember();

        $this->setAvailableViews([
            'list' => $this->listView
        ]);
        $this->setCurrentView($this->getAvailableView('list'));
        $this->setDataProvider($this->getModelSearch()->searchFacilitatorUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Facilitators'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'fromAction' => 'facilitator-users'
            ]);
    }

    /**
     * Lists all inactive users.
     * @return string
     */
    public function actionInactiveUsers()
    {
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setUpLayout('list');
        } else {
            $this->setUpLayout('list');
        }

        Url::remember();

        $this->setAvailableViews([
            'grid' => $this->gridView
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setDataProvider($this->getModelSearch()->searchInactiveUsers(Yii::$app->request->getQueryParams()));
        $this->setCreateNewBtnParams();
        $this->setListsViewParams();
        $this->setTitleAndBreadcrumbs(AmosAdmin::t('amosadmin', 'Inactive users'));
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'fromAction' => 'inactive-users'
            ]);
    }

    /**
     * Override default delete because it is not allowed to delete a user profile. It can only be deactivated.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        Yii::$app->getSession()->addFlash('danger',
            AmosAdmin::t('amosadmin', 'A user profile can only be deactivated. It cannot be deleted.'));
        return $this->redirect(Url::previous());
    }

    /**
     * This action deactivate an user profile.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeactivateAccount($id)
    {
        $this->model = $this->findModel($id);
        $this->model->setScenario(UserProfile::SCENARIO_REACTIVATE_DEACTIVATE_USER);

        if ($this->model->isDeactivated()) {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'This user profile is already deactivated.'));
            return $this->redirect(Url::previous());
        }

        $isLoggedUser = (Yii::$app->getUser()->getId() == $this->model->user_id);

        if (!Yii::$app->user->can('DeactivateAccount', ['model' => $this->model])) {
            if ($isLoggedUser && (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI'))) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'You cannot deactivate your user profile.'));
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'You have not the permission to deactivate an user profile.'));
            }
            return $this->redirect(Url::previous());
        }

        if ($isLoggedUser && (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_UTENTI'))) {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'You cannot deactivate your user profile.'));
            return $this->redirect(Url::previous());
        }

        $ok = $this->model->deactivateUserProfile();
        $subjectView = '@vendor/open20/amos-admin/src/mail/user/deactivateaccount-subject';
        $contentView = '@vendor/open20/amos-admin/src/mail/user/deactivateaccount-html';
        UserProfileUtility::sendMail($this->model, $subjectView, $contentView);
        UserProfileUtility::deassignRoleFacilitator($this->model);

        if ($ok) {
            Yii::$app->getSession()->addFlash('success',
                AmosAdmin::t('amosadmin', 'User profile deactivated successfully.'));
            if ($isLoggedUser) {
                return $this->redirect(['/' . AmosAdmin::getModuleName() . '/security/logout']);
            }
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'Error while deactivating user profile.'));
        }

        return $this->redirect(Url::previous());
    }

    /**
     * This action reactivate an user profile.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionReactivateAccount($id)
    {
        $this->model = $this->findModel($id);
        $this->model->setScenario(UserProfile::SCENARIO_REACTIVATE_DEACTIVATE_USER);

        if ($this->model->isActive()) {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'This user profile is already active.'));
            return $this->redirect(Url::previous());
        }

        if (!Yii::$app->user->can('ADMIN') && !Yii::$app->user->can('AMMINISTRATORE_UTENTI')) {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'You have not the permission to reactivate an user profile.'));
            return $this->redirect(Url::previous());
        }

        $ok = $this->model->activateUserProfile();
        $reactRequest = $this->model->userProfileReactivationRequest;
        if ($reactRequest) {
            $reactRequest->delete();
        }

        if ($ok) {
            Yii::$app->getSession()->addFlash('success',
                AmosAdmin::t('amosadmin', 'User profile reactivated successfully.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosAdmin::t('amosadmin', 'Error during reactivation of the user profile.'));
        }

        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionRejectReactivationRequest($id)
    {
        $reactRequ = UserProfileReactivationRequest::findOne(['user_profile_id' => $id]);
        if ($reactRequ) {
            $reactRequ->delete();
        } else throw new NotFoundHttpException();
        return $this->redirect(Url::previous());
    }

    /**
     * Action that check if there is already a default facilitator in the system.
     * @param int $id The user profile id (N.B. NOT THE USER ID!!!)
     * @return string
     * @throws AdminException
     * @throws NotFoundHttpException
     */
    public function actionDefFacilitatorPresent($id)
    {
        if (!Yii::$app->request->getIsAjax()) {
            throw new AdminException(AmosAdmin::t('amosadmin', 'The request is not via AJAX'));
        }
        if (!Yii::$app->request->getIsPost()) {
            throw new AdminException(AmosAdmin::t('amosadmin', 'The request is not via POST method'));
        }
        $this->model = $this->findModel($id);
        $facilitatorUserProfile = $this->model->getDefaultFacilitator();
        return json_encode([
            'defaultFaciliatorPresent' => (!is_null($facilitatorUserProfile) ? 1 : 0),
            'facilitatorNameSurname' => (!is_null($facilitatorUserProfile) ? $facilitatorUserProfile->getNomeCognome() : '')
        ]);
    }

    /**
     * Section Contacts (in edit or view mode) on user profile tab network
     * @param int $id
     * @param bool $isUpdate
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionContacts($id, $isUpdate = false)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);
            $this->model = $this->findModel($id);

            return $this->render('contacts',
                [
                    'model' => $this->model,
                    'isUpdate' => $isUpdate
                ]);
        }

        Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', 'This action must be called via AJAX.'));
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id
     * @return string
     */
    public function actionPasswordExpired($id)
    {
        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setUpLayout('mainFullsize');
        } else {
            $this->setUpLayout('main');
        }

        $messaggio = \Yii::t('amosadmin', '#new_password_is_expired');

        return $this->render('utenza_scaduta',
            [
                'message' => $messaggio,
                'user_id' => $id
            ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDropAccountByEmail($id)
    {
        $this->setUpLayout('main');
        $this->model = $this->findModel($id);

        //Avoid admin self-dropping
        if (\Yii::$app->user->can('ADMIN') && $id == \Yii::$app->user->id) {
            throw new \Exception('Hey! Can\'t Drop ADMIN User');
        }
        if (!\Yii::$app->user->can('ADMIN') && $this->model->user_id != Yii::$app->user->id) {
            throw new \Exception('Not allowed to drop other users');
        }


        if (\Yii::$app->request->isPost) {
            $ok = UserProfileMailUtility::sendEmailDropAccountRequest($this->model);
            if ($ok) {
                \Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', "Ti abbiamo inviato una email per completare la cancellazione dalla piattaforma, hai a disposizione 24 ore per completare l'operazione"));

            }
            return $this->redirect(['update', 'id' => $id]);
        }
        return $this->render('drop-account-by-email', ['model' => $this->model]);
    }


    /**
     * @param null $id
     * @param null $token
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDropAccount($id = null, $token = null)
    {

        $authorized = false;
        $confirm = \Yii::$app->request->post('confirm');


        //check if the token is valid
        if (!empty($token)) {
            $id = UserProfile::checkDeleteToken($token);
            if (!empty($id)) {
                $authorized = true;
                if (!$confirm) {
                    $model = $this->findModel($id);
                    $this->setUpLayout('main');
                    return $this->render('confirm-drop-account-by-email', ['model' => $model]);
                }

            }
        }

        if (empty($id)) {
            throw new ForbiddenHttpException(AmosAdmin::t('amosadmin', "Access denied"));
        }
        //Avoid admin self-dropping
        if (\Yii::$app->user->can('ADMIN') && $id == \Yii::$app->user->id) {
            throw new \Exception('Hey! Can\'t Drop ADMIN User');
        }

        $this->setUpLayout('form');

        $user = $this->findModel($id);
        $model = new DropAccountForm();

        if ($authorized || Yii::$app->request->isPost) {
            if (($authorized && $confirm) || ($model->load(Yii::$app->request->post()) && $model->validate())) {
                //New drop instance
                $dropController = new UserDropController('user_drop', $this->module);

                $moduleAdmin = \Yii::$app->getModule(AmosAdmin::getModuleName());

                // Send a report of user's assignments via mail if he/she has many of them
                if (!empty($moduleAdmin) && $moduleAdmin->sendUserAssignmentsReportOnDelete) {
                    // Security Policy copied from dropEverything() function below, so the email won't be sent
                    // if the function will throw an exception.
                    $this->sendUserAssignmentsReport($user->user_id, \Yii::$app->user->id);
                }

                if (!\Yii::$app->user->can('ADMIN') && $user->user_id != Yii::$app->user->id) {
                    throw new \Exception('Not allowed to drop other users');
                }

                //Irreversible action of user drop
                if (!empty($moduleAdmin) && $moduleAdmin->hardDelete) {
                    $dropController->dropEverything($user->user_id);
                } else {
                    $dropController->softDropEverything($user->user_id);
                }

                Yii::$app->getSession()->addFlash('success', AmosAdmin::t('amosadmin', 'Account Deleted.'));

                //Back to home because youre not logged anymore
                $redirectUrl = Url::home();
                if (\Yii::$app->user->can('ADMIN')) {
                    $redirectUrl = '/dashboard';
                }
                return $this->redirect($redirectUrl);
            } else {
                return $this->render('drop-account', ['model' => $model, 'id' => $id]);
            }
        } else {
            return $this->render('drop-account', ['model' => $model, 'id' => $id]);
        }
    }

    /**
     * @param $userId
     * @param $userRequestId
     */
    private function sendUserAssignmentsReport($userId, $userRequestId)
    {
        $userAssignments = $this->listUserAssignments($userId);

        // Se l'utente che sta per essere cancellato ha attivita' associate,
        // viene inviata mail agli utenti admin, altrimenti non viene inviato nulla
        if (!empty($userAssignments)) {

            // Cerco i profili dell'utente che sta per essere cancellato e l'utente che ha inviato la richiesta
            // di cancellazione
            $userProfile = UserProfile::findOne(['user_id' => $userId]);
            $userProfileRequestId = UserProfile::findOne(['user_id' => $userRequestId]);

            // Se l'utente che sta per essere cancellato e' lo stesso che ha inviato la richiesta
            // (cancellazione richiesta dallo stesso utente), l'inizio del messaggio lo specifica
            // altrimenti viene mostrato anche il nome di chi ha avviato la procedura.
            if ($userId == $userRequestId) {
                $reportMessage = "L'utente <b>{$userProfile->getNomeCognome()}</b> ha desiderato cancellarsi dalla piattaforma.<br />
                            Di seguito è presente una lista delle attività da lui svolte.<br /><br /><hr /><br />";
            } else {
                $reportMessage = "L'utente <b>{$userProfile->getNomeCognome()}</b> è stato cancellato dalla piattaforma da parte di <b>{$userProfileRequestId->getNomeCognome()}</b>.<br />
                            Di seguito è presente una lista delle attività da lui svolte.<br /><br /><hr /><br />";
            }

            // Se l'utente che sta per essere cancellato era facilitatore di utenti, vengono mostrati nel corpo della mail
            if (array_key_exists('facilitatorOfUsers', $userAssignments) &&
                !empty($userAssignments['facilitatorOfUsers'])) {
                $reportMessage .= "L'utente cancellato era <b>facilitatore</b> dei seguenti utenti:<br />";
                foreach (($userAssignments['facilitatorOfUsers']) as $user) {
                    $reportMessage .= "{$user['nome']} {$user['cognome']} (id: {$user['user_id']})<br />";
                }
                $reportMessage .= "<br /><hr /><br />";
            }

            // Se l'utente che sta per essere cancellato era CM di community, vengono mostrate nel corpo della mail
            if (array_key_exists('communityManagerOfCommunities', $userAssignments) &&
                !empty($userAssignments['communityManagerOfCommunities'])) {
                $reportMessage .= "L'utente cancellato era <b>community manager</b> delle seguenti community:<br />";
                foreach (($userAssignments['communityManagerOfCommunities']) as $community) {
                    $reportMessage .= "{$community['community_nome']} (id: {$community['community_id']})<br />";
                }
                $reportMessage .= "<br /><hr /><br />";
            }

            // Se l'utente che sta per essere cancellato era referente di organizzazioni, vengono mostrate nel corpo della mail
            if (array_key_exists('contactOfOrganizations', $userAssignments) &&
                !empty($userAssignments['contactOfOrganizations'])) {
                $reportMessage .= "L'utente cancellato era <b>referente</b> delle seguenti organizzazioni:<br />";
                foreach (($userAssignments['contactOfOrganizations']) as $organization) {
                    $reportMessage .= "{$organization['organization_name']} (id: {$organization['organization_id']})<br />";
                }
                $reportMessage .= "<br /><hr /><br />";
            }

            $reportMessage .= "Per queste attività, potrebbe essere necessario trovare un sostituto.";

            // Ottenimento la lista di utenti (e email) con ruolo admin all'interno dell'app
            $listAdminUsers = Yii::$app->authManager->getUserIdsByRole('ADMIN');
            $listAdminUsersEmail = [];

            foreach ($listAdminUsers as $userId) {
                $listAdminUsersEmail[] = User::findOne(['id' => $userId])->email;
            }

            // Invio email
            $email = new Email();
            $from = '';
            $platformName = '';

            // Ottengo nome applicazione/piattaforma per l'inserimento nel titolo della mail
            if (isset(\Yii::$app->name)) {
                $platformName = \Yii::$app->name;
                $platformName = " {$platformName}";
            }

            $subject = "Notifica cancellazione utente {$userProfile->getNomeCognome()} con attività rilevanti per la piattaforma{$platformName}";
            if (isset(\Yii::$app->params['adminEmail'])) {
                //use default platform email assistance
                $from = \Yii::$app->params['adminEmail'];
            }

            // Invio Email da Mail Manager
            try {
                $email->sendMail($from, $listAdminUsersEmail, $subject, $reportMessage);
            } catch (\Exception $ex) {
                \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            }
        }
    }

    /**
     * Lista di utenti facilitati, di community in cui è community manager o di organizzazioni in cui è referente
     * l'utente passato come parametro
     * @param int $userId
     * @return array $assignmentsFound
     */
    private function listUserAssignments($userId)
    {
        /**
         * La lista di assegnazioni trovate all'interno della piattaforma:
         * l'array sara' strutturato in questa maniera:
         * $assignmentsFound = [
         *      'facilitatorOfUsers' => [...], // la lista di utenti per cui e' facilitatore
         *      'communityManagerOfCommunities' => [...], // la lista di id di community in cui e' community manager
         *      'contactOfOrganizations' => [...], // la lista di id di organizzazioni in cui e' contatto
         * ]
         */
        $assignmentsFound = [];

        $facilitatorePerUtenti = UserProfile::findAll(['facilitatore_id' => $userId]);
        if (!empty($facilitatorePerUtenti)) {
            foreach ($facilitatorePerUtenti as $utente) {
                $assignmentsFound['facilitatorOfUsers'][] = [
                    'user_id' => $utente->user_id,
                    'nome' => $utente->nome,
                    'cognome' => $utente->cognome,
                ];
            }
        }

        $moduleCommunity = \Yii::$app->getModule('community');
        if (!empty($moduleCommunity)) {
            $communityManagerPerCommunity = CommunityUserMm::findAll(['user_id' => $userId, 'role' => Community::ROLE_COMMUNITY_MANAGER]);
            foreach ($communityManagerPerCommunity as $communityUserMm) {
                $assignmentsFound['communityManagerOfCommunities'][] = [
                    'community_id' => $communityUserMm->community_id,
                    'community_nome' => Community::findOne(['id' => $communityUserMm->community_id])->name,
                ];
            }
        }

        $moduleOrganizations = \Yii::$app->getModule('organizations');
        if (!empty($moduleOrganizations)) {
            $referentePerOrganizzazioni = Organizations::findAll(['operating_referent_id' => $userId]);
            foreach ($referentePerOrganizzazioni as $organizzazione) {
                $assignmentsFound['contactOfOrganizations'][] = [
                    'organization_id' => $organizzazione->id,
                    'organization_name' => $organizzazione->name,
                ];
            }
        }

        return $assignmentsFound;
    }

    /**
     * Request authorization to user connect to $serviceName
     * Stores authorization data in SocialAuthServices table
     * Synchronize the requested service calling synchronizeGoogleService method of socialAuth module
     *
     * @param int|null $id
     * @param string $serviceName
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEnableGoogleService($id = null, $serviceName = '')
    {
        $this->setUpLayout('empty');
        $socialAuth = Yii::$app->getModule('socialauth');
        $authUrl = null;
        $message = '';
        if (!is_null($socialAuth)) {
            /** @var \Google_Client $client */
            $client = $socialAuth->getClient('google');
            if (!is_null($client)) {
                if (is_null($id)) {
                    $userId = Yii::$app->user->id;
                    $this->model = UserProfile::findOne(['user_id' => $userId]);
                } else {
                    $this->model = $this->findModel($id);
                }
                if (!is_null($this->model)) {
                    $socialAuthUser = $this->model->getSocialAuthUsers()->andWhere(['provider' => 'google'])->one();
                    if ($socialAuthUser) {
                        if (!empty($serviceName)) {
                            Yii::$app->session->set('serviceName', $serviceName);
                        } else {
                            if (Yii::$app->session->has('serviceName')) {
                                $serviceName = Yii::$app->session->get('serviceName');
                            }
                        }
                        $service = $socialAuthUser->getServices()->andWhere(['service' => $serviceName])->one();
                        if (!$service) {
                            // Request authorization from the user.
                            $client->setRedirectUri(Yii::$app->urlManager->createAbsoluteUrl('admin/user-profile/enable-google-service'));
                            if ($serviceName == 'contacts') {
                                $client->addScope("https://www.googleapis.com/auth/" . $serviceName . '.readonly');
                            } else {
                                $client->setScopes("https://www.googleapis.com/auth/" . $serviceName);
                            }

                            $client->setAccessType('offline');

                            if (!empty($_GET['code'])) {
                                $get = filter_input(INPUT_GET, 'code');
                                $client->fetchAccessTokenWithAuthCode($get);
                                $accessToken = $client->getAccessToken();
                                if (!empty($accessToken) && !empty($accessToken['access_token'])) {
                                    $message .= 'accesstoken ' . $accessToken['access_token'];
                                } else {
                                    $message .= 'no access_token';
                                }

                                if (!$client->getRefreshToken()) {
                                    //No refresh token generated, the access token is old/deleted/invalid
                                    //revoke token and ask for a new authorization to store in social auth service
                                    $client->revokeToken($accessToken['access_token']);
                                    $authUrl = $client->createAuthUrl();
                                    return $this->redirect($authUrl);
                                }
                            } else {
                                $authUrl = $client->createAuthUrl();
                                return $this->redirect($authUrl);
                            }

                            if (isset($accessToken) && !empty($accessToken['refresh_token'])) {
                                $serviceName = Yii::$app->session->get('serviceName');
                                $service = new SocialAuthServices();
                                $service->service = $serviceName;
                                $service->social_users_id = $socialAuthUser->id;
                                $service->access_token = $accessToken['access_token'];
                                $service->token_type = $accessToken['token_type'];
                                $service->expires_in = $accessToken['expires_in'];
                                $service->refresh_token = $accessToken['refresh_token'];
                                $service->token_created = $accessToken['created'];
                                if ($service->save()) {
                                    $message = AmosAdmin::t('amosadmin', 'Impostazione salvata con successo');
                                }
                                Yii::$app->session->offsetUnset('serviceName');
                            }
                        }
                        if ($service) {
                            try {
                                $message = $socialAuth->synchronizeGoogleService($service);
                            } catch (Exception $e) {
                                $message = $e->getMessage();
                            }
                        }
                    }
                }
            }
        }
        return $this->render('enable-google-service', ['message' => $message]);
    }

    /**
     * Revoke user authorization to connect to google $serviceName
     * Delete all records created by service synchronization on user google account
     * Delete data of user service previously stored in SocialAuth Services table
     *
     * @param int $id
     * @param string $serviceName
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDisableGoogleService($id, $serviceName = 'calendar')
    {
        $this->setUpLayout('empty');
        $this->model = $this->findModel($id);
        $socialAuth = Yii::$app->getModule('socialauth');
        $authUrl = null;
        if (!is_null($socialAuth)) {
            $client = $socialAuth->getClient('google');
            if (!is_null($client)) {
                $socialAuthUser = $this->model->getSocialAuthUsers()->andWhere(['provider' => 'google'])->one();
                if ($socialAuthUser) {
                    $service = $socialAuthUser->getServices()->andWhere(['service' => $serviceName])->one();
                    if ($service) {
                        $socialAuth->removeGoogleService($service);
                        $client->revokeToken($service->access_token);
                        $service->delete();
                    }
                }
            }
        }
        if (Yii::$app->request->isPost) {
            return json_encode(true);
        }
        return $this->render('enable-google-service');
    }

    /**
     * Given userProfile id, check if the specified $serviceName provided by $provider is active for the user.
     * Returns json, with enabled
     *
     * @param int $id - the user profile Id
     * @param string $provider
     * @param string $serviceName
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetSocialServiceStatus($id, $provider = 'google', $serviceName = '')
    {
        $service['enabled'] = -1;
        $socialAuth = Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            $this->model = $this->findModel($id);
            $socialAuthUser = $this->model->getSocialAuthUsers()->andWhere(['provider' => $provider])->one();
            if ($socialAuthUser) {
                $service['enabled'] = $socialAuthUser->getServices()->andWhere(['service' => $serviceName])->count();
                if ($service['enabled']) {
                    $action = 'disable';
                } else {
                    $action = 'enable';
                }
                $service['url'] = '/' . AmosAdmin::getModuleName() . '/user-profile/' . $action . '-' . $provider . '-service?id=' . $this->model->id . '&serviceName=' . $serviceName;
            }
        }
        return json_encode($service);
    }

    /**
     * Given userProfile id, check if the specified $serviceName provided by $provider is active for the user.
     * Returns json, with enabled
     *
     * @param $id - the user profile Id
     * @param null|string $provider
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGetSocialUser($id, $provider = '')
    {
        $socialAuthUser = 0;
        $socialAuth = Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            $this->model = $this->findModel($id);
            $socialAuthUserQuery = $this->model->getSocialAuthUsers();
            if (!empty($provider)) {
                $socialAuthUserQuery->andWhere(['provider' => $provider]);
            }
            $socialAuthUser = $socialAuthUserQuery->count();
        }
        return json_encode($socialAuthUser);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCompleteProfile()
    {
        $this->setUpLayout('main');
        $this->model = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
        if (empty($this->model)) {
            throw new NotFoundHttpException();
        }
        return $this->render('complete_profile', ['model' => $this->model]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSendRequestExternalFacilitator($id, $idToAssign = null)
    {

        $this->setUpLayout('main');
        $model = $this->findModel($id);
        /** @var UserProfile $userProfileModel */
        $dataProvider = $model->searchExternalFacilitator();

        if ($idToAssign) {
            $externalFacilitator = new UserProfileExternalFacilitator();
            $externalFacilitator->user_profile_id = $id;
            $externalFacilitator->external_facilitator_id = $idToAssign;
            $externalFacilitator->status = UserProfileExternalFacilitator::EXTERNAL_FACILITATOR_REQUEST;
            if ($externalFacilitator->save(false)) {
                UserProfileMailUtility::sendEmailRequestEexternalFacilitator($externalFacilitator);
                $facilitatorName = $externalFacilitator->externalFacilitator->nomeCognome;
                \Yii::$app->session->addFlash('success',
                    AmosAdmin::t('amosadmin',
                        "La tua richiesta è stata inviata al Facilitatore esterno {nomeCognome}. Riceverai da lui un riscontro.",
                        [
                            'nomeCognome' => $facilitatorName
                        ]));
                return $this->redirect(['update', 'id' => $id]);
            }
        }


        return $this->render('associate_external_facilitator',
            [
                'model' => $model,
                'dataProvider' => $dataProvider
            ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionAcceptRequest($id, $fromMyactivities = false)
    {
        $userProfileExternalFacilitator = UserProfileExternalFacilitator::findOne($id);
        if (empty($userProfileExternalFacilitator)) {
            throw new NotFoundHttpException();
        }

        if ($userProfileExternalFacilitator) {
            if ($userProfileExternalFacilitator->externalFacilitator->user_id !== \Yii::$app->user->id && !\Yii::$app->user->can('ADMIN')) {
                throw new ForbiddenHttpException();
            }
            if ($userProfileExternalFacilitator->status == UserProfileExternalFacilitator::EXTERNAL_FACILITATOR_REQUEST) {
                $userProfileExternalFacilitator->status = UserProfileExternalFacilitator::EXTERNAL_FACILITATOR_ACCEPTED;
                if ($userProfileExternalFacilitator->save(false)) {
                    $profile = $userProfileExternalFacilitator->userProfile;
                    $oldFaclitatorId = null;
                    if ($profile) {
                        $oldFaclitatorId = $profile->external_facilitator_id;
                        $profile->external_facilitator_id = $userProfileExternalFacilitator->external_facilitator_id;
                        if ($profile->save(false)) {
                            $profile->connectProfileToExternalFacilitator();
                            \Yii::$app->session->addFlash('success',
                                AmosAdmin::t('amosadmin', 'Hai accettato la richiesta correttamente'));
                            UserProfileMailUtility::sendEmailAcceptExternalFacilitator($userProfileExternalFacilitator);
                            UserProfileMailUtility::sendEmailChangeExternalFacilitator($userProfileExternalFacilitator,
                                $oldFaclitatorId);
                            if ($fromMyactivities) {
                                return $this->redirect(['/myactivities/my-activities/index']);
                            }
                            return $this->redirect(['view', 'id' => $userProfileExternalFacilitator->user_profile_id]);
                        }
                    }
                }
            }
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionRejectRequest($id, $fromMyactivities = false)
    {
        $userProfileExternalFacilitator = UserProfileExternalFacilitator::findOne($id);
        if (empty($userProfileExternalFacilitator)) {
            throw new NotFoundHttpException();
        }
        if ($userProfileExternalFacilitator) {
            if ($userProfileExternalFacilitator->externalFacilitator->user_id !== \Yii::$app->user->id && !\Yii::$app->user->can('ADMIN')) {
                throw new ForbiddenHttpException();
            }
            if ($userProfileExternalFacilitator->status == UserProfileExternalFacilitator::EXTERNAL_FACILITATOR_REQUEST) {
                $userProfileExternalFacilitator->status = UserProfileExternalFacilitator::EXTERNAL_FACILITATOR_REJECTED;
                if ($userProfileExternalFacilitator->save(false)) {
                    \Yii::$app->session->addFlash('success',
                        AmosAdmin::t('amosadmin', 'Hai rifiutato la richiesta correttamente'));

                    UserProfileMailUtility::sendEmailRejectExternalFacilitator($userProfileExternalFacilitator);
                    if ($fromMyactivities) {
                        return $this->redirect(['/myactivities/my-activities/index']);
                    }
                    return $this->redirect(['view', 'id' => $userProfileExternalFacilitator->user_profile_id]);
                }
            }
        }
        return $this->redirect(Url::previous());
    }


    /**
     * @param $id
     * @param null $redirectUrl
     * @return \yii\web\Response
     */
    public function actionConnectSpid($id, $redirectUrl = null)
    {
        if(empty($redirectUrl)){
            $redirectUrl = \Yii::$app->params['platform']['backendUrl'] . '/admin/user-profile/update?id=' . $id . '#tab-settings';
        }
        \Yii::$app->session->set('connectSpidToProfile', 1);
        \Yii::$app->session->set('redirect_url_spid', $redirectUrl);
        return $this->redirect(['/socialauth/shibboleth/endpoint', 'confirm' => true]);

    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionModifyEmail($id)
    {
        $this->setUpLayout('form');
        $userProfile = $this->findModel($id);
        $user = $userProfile->user;
        $oldEmail = $user->email;
        $model = new UserOtpCode();
        $error = false;

        if ($user->load(\Yii::$app->request->post()) || $model->load(\Yii::$app->request->post())) {
            if($oldEmail == $user->email){
                $user->addError('email', AmosAdmin::t('amosadmin', "L'email inserita deve essere diversa dalla precedente."));
                $error = true;
            }
            $btnSaveCode = \Yii::$app->request->post('save-code');
//            pr($btnSaveCode); die;
            if (empty($btnSaveCode) && isset(\Yii::$app->request->post('UserOtpCode')['auth_code'])) {
                $code = \Yii::$app->request->post('UserOtpCode')['auth_code'];
                if (UserOtpCode::isValidCodice($code, UserOtpCode::TYPE_AUTH_EMAIL)) {
                    if (!UserOtpCode::isExpired($code, UserOtpCode::TYPE_AUTH_EMAIL)) {
                        if ($user->load(\Yii::$app->request->post()) && $user->validate('email')) {
                            $user->save(false);
                            \Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', 'La nuova email inserita è stata modificata con successo'));
                            return $this->redirect(['update', 'id' => $id]);
                        }
                    }else{
                        $model->addError('auth_code', AmosAdmin::t('amosadmin', 'Expired code'));
                    }
                } else {
                    $model->addError('auth_code', AmosAdmin::t('amosadmin', 'Il codice OTP inserito non è valido, inserire quello corretto oppure richiederne uno nuovo'));
                }

                return $this->render('modify_email', ['user' => $user, 'model' => $model, 'inserisciCodice' => true]);
            } else if(!empty($btnSaveCode) && !$error){
                if ($user->load(\Yii::$app->request->post()) && $user->validate('email')) {
                    $subject = AmosAdmin::t('amosadmin', "Modifica indirizzo email per l'utente {nome} {cognome}",[
                        'nome' => $userProfile->nome,
                        'cognome' => $userProfile->cognome,
                    ]);
                    $text = "<p>" . AmosAdmin::t('amosadmin', "È stato richiesto il cambio dell'indirizzo email per l'utente <strong>{nome}</strong> <strong>{cognome}</strong> iscritto alla piattaforma <strong>{appname}</strong>.",[
                        'nome' => $userProfile->nome,
                        'cognome' => $userProfile->cognome,
                        'appname' => \Yii::$app->name,
                        ]) . "</p>";
                    UserOtpCode::sendEmailAuthentication($user->email, $subject, $text, $user);
                    \Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', "È stata inviata una email al nuovo indirizzo di posta <strong>{nuovaEmail}</strong>, inserisci nell'apposito campo il codice OTP ricevuto",[
                        'nuovaEmail' => $user->email
                    ]));
                    return $this->render('modify_email', ['user' => $user, 'model' => $model, 'inserisciCodice' => true]);
                }
            }
        }
        return $this->render('modify_email', ['user' => $user, 'model' => $model, 'inserisciCodice' => false]);
    }
}