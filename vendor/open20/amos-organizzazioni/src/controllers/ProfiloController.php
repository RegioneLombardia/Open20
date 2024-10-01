<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\controllers
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\utility\EmailUtility;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use open20\amos\organizzazioni\widgets\JoinProfiloWidget;
use open20\amos\organizzazioni\widgets\ProfiloCardWidget;
use raoul2000\workflow\base\WorkflowException;
use open20\amos\organizzazioni\assets\OrganizzazioniAsset;
use Yii;
use yii\base\Event;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\ForbiddenHttpException;
use open20\amos\community\models\CommunityType;

/**
 * Class ProfiloController
 * This is the class for controller "ProfiloController".
 * @package open20\amos\organizzazioni\controllers
 */
class ProfiloController extends base\ProfiloController
{

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;

    const EVENT_BEFORE_CREATE_COMMUNITY = 'beforeCreateCommunity';
    const EVENT_AFTER_CREATE_COMMUNITY = 'afterCreateCommunity';

    protected $defaultAssociaM2mStatus = '';

    protected $forceSendWelcomeInJoinOrganization = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /** @var ProfiloUserMm $profiloUserMmModel */
        $profiloUserMmModel = $this->organizzazioniModule->createModel('ProfiloUserMm');
        $this->defaultAssociaM2mStatus = $profiloUserMmModel::STATUS_INVITE_IN_PROGRESS;

        OrganizzazioniAsset::register(Yii::$app->view);

        $this->setMmTableName($this->organizzazioniModule->model('ProfiloUserMm'));
        $this->setStartObjClassName($this->organizzazioniModule->model('Profilo'));
        $this->setMmStartKey('profilo_id');
        $this->setTargetObjClassName(AmosAdmin::instance()->model('User'));
        $this->setMmTargetKey('user_id');
        $this->setRedirectAction('update');
        $this->setModuleClassName(Module::className());
        $this->setCustomQuery(true);
        $this->setTargetUrlInvitation('/invitations/invitation/index-all/');
        $this->setInvitationModule(Module::getModuleName());
        $this->on(M2MEventsEnum::EVENT_BEFORE_DELETE_M2M, [$this, 'beforeDeleteM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_DELETE_M2M, [$this, 'afterDeleteM2m']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_CANCEL_ASSOCIATE_M2M, [$this, 'beforeCancelAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_ASSOCIATE_M2M, [$this, 'beforeAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_ASSOCIATE_M2M, [$this, 'afterAssociateM2m']);

        $this->setUpLayout('main');
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
                            'user-network',
                            'organization-employees',
                            'my-organizations'
                        ],
                        'roles' => ['PROFILO_READ']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'elimina-m2m'
                        ],
                        'roles' => ['REMOVE_PROFILO_FROM_USER_PERMISSION']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'annulla-m2m',
                            'associate-organization-m2m',
                            'join-organization'
                        ],
                        'roles' => ['ASSOCIATE_PROFILO_TO_USER_PERMISSION']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'associate-organizations-to-project-m2m',
                            'associate-organizations-to-project-task-m2m',
                        ],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'change-user-role-area',
                            'create-community',
                            'organization-employees',
                        ],
                        'roles' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'BASIC_USER']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'accept-user',
                            'reject-user',
                        ],
                        'roles' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'CONFIRM_ORGANIZZAZIONI_OR_SEDI_USER_REQUEST']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'associa-m2m',
                        ],
                        'roles' => ['ADD_EMPLOYEE_TO_ORGANIZATION_PERMISSION']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'profilo-to-publish',
                        ],
                        'roles' => ['AMMINISTRATORE_ORGANIZZAZIONI']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'validate-profilo',
                            'reject-profilo',
                        ],
                        'roles' => ['PROFILO_VALIDATOR']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'download-import-template',
                        ],
                        'roles' => ['IMPORT_ORGANIZATIONS']
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
     * disabled csrf token for send-message
     */

    public function beforeAction($action)
    {
        if ($action->id == 'user-network') {
            $this->enableCsrfValidation = false;
            // Yii::$app->controller->enableCsrfValidation = FALSE;
        }

        if (\Yii::$app->user->isGuest) {
            $titleSection = Module::t('amosorganizzazioni', 'Organizzazioni');
            $urlLinkAll = '';

            $labelSigninOrSignup = Module::t('amosorganizzazioni', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = Module::t(
                'amosorganizzazioni',
                '#beforeActionCtaLoginRegisterTitle',
                ['platformName' => \Yii::$app->name]
            );
            $labelSignin = Module::t('amosorganizzazioni', '#beforeActionCtaLogin');
            $titleSignin = Module::t(
                'amosorganizzazioni',
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
                isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                    : \Yii::$app->params['platform']['backendUrl'] . '/' . AmosAdmin::getModuleName() . '/security/login',
                [
                    'title' => $titleLink
                ]
            );
            $subTitleSection = Html::tag(
                'p',
                Module::t(
                    'amosorganizzazioni',
                    '#beforeActionSubtitleSectionGuest',
                    ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                )
            );
        } else {
            $titleSection = Module::t('amosorganizzazioni', 'Organizzazioni');
            $labelLinkAll = Module::t('amosorganizzazioni', 'Tutte le organizzazioni');
            $urlLinkAll = '/organizzazioni/profilo/index';
            $titleLinkAll = Module::t('amosorganizzazioni', 'Visualizza la lista delle organizzazioni');

            $subTitleSection = Html::tag('p', Module::t('amosorganizzazioni', '#beforeActionSubtitleSectionLogged'));
        }

        $labelCreate = Module::t('amosorganizzazioni', 'Nuova');
        $titleCreate = Module::t('amosorganizzazioni', 'Crea una nuova organizzazione');
        $labelManage = Module::t('amosorganizzazioni', 'Gestisci');
        $titleManage = Module::t('amosorganizzazioni', 'Gestisci le organizzazioni');
        $urlCreate = '/organizzazioni/profilo/create';
        $urlManage = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'organizzazioni',
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
     * @param Profilo $model
     * @return string
     */
    public function getRappresentanteLegaleAjaxUrl($model)
    {
        return Url::to(['/' . AmosAdmin::getModuleName() . '/user-profile-ajax/ajax-user-list']);
    }

    /**
     * @param Profilo $model
     * @return string
     */
    public function getReferenteOperativoAjaxUrl($model)
    {
        return Url::to(['/' . AmosAdmin::getModuleName() . '/user-profile-ajax/ajax-user-list']);
    }

    /**
     * @param Profilo $model
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociaM2mQuery($model)
    {
        /** @var Module $organizzazioniModule */
        $organizzazioniModule = Module::instance();
        $inviteUserOfOrganizationParent = $organizzazioniModule->inviteUserOfOrganizationParent;
        $isSubOrganization = !empty($model->parent_id);

        /** @var ActiveQuery $query */
        $query = $model->getAssociationTargetQuery($model->id);
        $post = Yii::$app->request->post();
        if (isset($post['genericSearch']) && (strlen($post['genericSearch']) > 0)) {
            $userProfileTable = UserProfile::tableName();
            $query->andWhere([
                'or',
                ['like', $userProfileTable . '.cognome', $post['genericSearch']],
                ['like', $userProfileTable . '.nome', $post['genericSearch']],
                ['like', "CONCAT( " . $userProfileTable . ".nome , ' ', " . $userProfileTable . ".cognome )", $post['genericSearch']],
                ['like', "CONCAT( " . $userProfileTable . ".cognome , ' ', " . $userProfileTable . ".nome )", $post['genericSearch']],
                ['like', $userProfileTable . '.codice_fiscale', $post['genericSearch']],
                ['like', $userProfileTable . '.domicilio_indirizzo', $post['genericSearch']],
                ['like', $userProfileTable . '.indirizzo_residenza', $post['genericSearch']],
                ['like', $userProfileTable . '.domicilio_localita', $post['genericSearch']],
                ['like', $userProfileTable . '.domicilio_cap', $post['genericSearch']],
                ['like', $userProfileTable . '.cap_residenza', $post['genericSearch']],
                ['like', $userProfileTable . '.numero_civico_residenza', $post['genericSearch']],
                ['like', $userProfileTable . '.domicilio_civico', $post['genericSearch']],
                ['like', $userProfileTable . '.telefono', $post['genericSearch']],
                ['like', $userProfileTable . '.cellulare', $post['genericSearch']],
                ['like', $userProfileTable . '.email_pec', $post['genericSearch']],
            ]);
        }

        if ($inviteUserOfOrganizationParent && $isSubOrganization) {
            /** @var ProfiloUserMm $profiloUserMmModel */
            $profiloUserMmModel = $this->organizzazioniModule->createModel('ProfiloUserMm');
            $profiloUserMmTable = $profiloUserMmModel::tableName();
            $query->innerJoin($profiloUserMmTable, $profiloUserMmTable . '.user_id = ' . User::tableName() . '.id')
                ->andWhere([$profiloUserMmTable . '.profilo_id' => $model->parent_id])
                ->andWhere([$profiloUserMmTable . '.deleted_at' => null]);
        }

        return $query;
    }

    /**
     * @param Event $event
     */
    public function beforeAssociateM2m($event)
    {
        if (strstr(Yii::$app->controller->action->id, 'associa-m2m')) {
            $this->setTargetUrl('associa-m2m');
            $this->setMmTableAttributesDefault([
                'status' => $this->defaultAssociaM2mStatus,
            ]);
        }
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     */
    public function afterAssociateM2m($event)
    {
        $urlPrevious = Url::previous();
        if (
            !strstr($urlPrevious, 'associate-organization-m2m') &&
            !strstr($urlPrevious, 'associate-organizations-to-project-m2m') &&
            !strstr($urlPrevious, 'associate-organizations-to-project-task-m2m')
        ) {
            $profiloId = Yii::$app->request->get('id');
            $userStatus = Yii::$app->request->get('userStatus');
            $emailTypeCustom = Yii::$app->request->get('emailTypeCustom');

            /** @var Profilo $profiloModel */
            $profiloModel = $this->organizzazioniModule->createModel('Profilo');

            /** @var ProfiloUserMm $profiloUserMmModel */
            $profiloUserMmModel = $this->organizzazioniModule->createModel('ProfiloUserMm');

            $profilo = $profiloModel::findOne($profiloId);

            $redirectUrl = ['/organizzazioni/profilo/update', 'id' => $profilo->id];
            if (!empty(\Yii::$app->request->get('redirectUrlAfterAssociate'))) {
                $redirectUrl = \Yii::$app->request->get('redirectUrlAfterAssociate');
            }

            $loggedUser = User::findOne(Yii::$app->getUser()->id);
            /** @var UserProfile $loggedUserProfile */
            $loggedUserProfile = $loggedUser->getProfile();

            $profiloUserMms = $profiloUserMmModel::find()->andWhere([
                'status' => $this->defaultAssociaM2mStatus,
                'profilo_id' => $profiloId,
                'email_sent' => 0,
            ])->all();
            $userStatus = (!is_null($userStatus) ? $userStatus : $profiloUserMmModel::STATUS_WAITING_OK_USER);
            if (!empty($emailTypeCustom)) {
                $emailType = $emailTypeCustom;
            } elseif ($userStatus == $profiloUserMmModel::STATUS_ACTIVE) {
                $emailType = EmailUtility::WELCOME;
            } else {
                $emailType = EmailUtility::INVITATION;
            }
            foreach ($profiloUserMms as $profiloUserMm) {
                /** @var ProfiloUserMm $profiloUserMm */
                $profiloUserMm->status = $userStatus;
                $profiloUserMm->email_sent = 1;
                $profiloUserMm->save(false);
                $userToInvite = $profiloUserMm->user;
                $emailUtil = new EmailUtility(
                    $emailType,
                    $profiloUserMm->role,
                    $profilo,
                    $userToInvite->userProfile->nomeCognome,
                    $loggedUserProfile->nomeCognome,
                    null,
                    $userToInvite->id
                );
                $subject = $emailUtil->getSubject();
                $text = $emailUtil->getText();
                $emailUtil->sendMail(null, $userToInvite->email, $subject, $text, [], []);


                //add user to community
                if ($this->organizzazioniModule->enableCommunityCreation && $this->organizzazioniModule->createCommunityAutomatically) {
                    if (!empty($profilo->community_id)) {
                        $communityModule = \Yii::$app->getModule('community');
                        if ($communityModule) {
                            OrganizzazioniUtility::addOrganizationCommunityUser($profilo, $userToInvite->id, $profilo->getBaseRole(), $communityModule);
                        }
                    }
                }
            }

            $this->setRedirectArray($redirectUrl);
        }
    }

    /**
     * @param $event
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function beforeDeleteM2m($event)
    {
        $profiloId = Yii::$app->request->get('id');
        $userId = Yii::$app->request->get('targetId');

        /** @var Profilo $profiloModel */
        $profiloModel = $this->organizzazioniModule->createModel('Profilo');
        /** @var ProfiloUserMm $profiloUserMmModel */
        $profiloUserMmModel = $this->organizzazioniModule->createModel('ProfiloUserMm');

        /** @var Profilo $profilo */
        $profilo = $profiloModel::findOne($profiloId);

        /** @var ProfiloUserMm $profiloUserMmRow */
        $profiloUserMmRow = $profiloUserMmModel::findOne(['profilo_id' => $profiloId, 'user_id' => $userId]);

        if ($this->organizzazioniModule->enableCommunityCreation) {
            $profilo->removeMembershipFromCommunities($userId);
        }

        // Remove all cwh permissions for domain = community
        $profilo->setCwhAuthAssignments($profiloUserMmRow, true);
    }

    /**
     * @param Event $event
     */
    public function afterDeleteM2m($event)
    {

        $this->setRedirectArray([Url::previous()]);
    }

    /**
     * @param Event $event
     */
    public function beforeCancelAssociateM2m($event)
    {
        $urlPrevious = Url::previous();
        $id = Yii::$app->request->get('id');
        if (strstr($urlPrevious, 'associate-organization-m2m')) {
            $this->setRedirectArray('/' . AmosAdmin::getModuleName() . '/user-profile/update?id=' . $id);
        }
        if (strstr($urlPrevious, 'associate-project-m2m')) {
            $this->setRedirectArray('/project_management/projects/update?id=' . $id . '#tab-organizations');
        }
        if (!empty(\Yii::$app->request->get('redirectCancelButton'))) {
            $redirectUrl = \Yii::$app->request->get('redirectCancelButton');
            $this->setRedirectArray($redirectUrl);
        }
    }

    /**
     * This method returns the query used in the organization-employees view
     * or OrganizationsMembersWidget to view organization employees.
     * @param Profilo $model
     * @param bool $isUpdate
     * @param array $showRoles
     * @param bool $excludeReferees
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getOrganizationEmployeesQuery($model, $isUpdate, $showRoles = [], $excludeReferees = false)
    {
        return OrganizzazioniUtility::getOrganizationEmployeesQuery(
            $model,
            $isUpdate,
            $showRoles,
            $this->organizzazioniModule,
            $excludeReferees
        );
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function actionAssociateOrganizationsToProjectM2m()
    {
        if (!empty(\Yii::$app->getModule('project_management'))) {
            $projectId = Yii::$app->request->get('id');
            Url::remember();

            $this->setMmTableName(\open20\amos\projectmanagement\models\ProjectsJoinedOrganizationsMm::className());
            $this->setStartObjClassName(\open20\amos\projectmanagement\models\Projects::className());
            $this->setMmStartKey('projects_id');
            $this->setTargetObjClassName($this->organizzazioniModule->model('Profilo'));
            $this->setMmTargetKey('organization_id');
            $this->setRedirectAction('/project_management/projects/update');
            $this->setOptions(['#' => 'tab-organizations']);
            $this->setTargetUrl('associa_organizations_to_project_m2m');
            $this->setCustomQuery(true);
            $this->setModuleClassName(Module::className());
            $this->setRedirectArray('/project_management/projects/update?id=' . $projectId . '#tab-organizations');
            return $this->actionAssociaM2m($projectId);
        } else {
            throw new \Exception(Module::t('organizations', 'The module project is not enabled'));
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function actionAssociateOrganizationsToProjectTaskM2m()
    {
        if (!empty(\Yii::$app->getModule('project_management'))) {
            $projectTaskId = Yii::$app->request->get('id');
            Url::remember();

            $this->setMmTableName(\open20\amos\projectmanagement\models\ProjectsTasksJoinedOrganizationsMm::className());
            $this->setStartObjClassName(\open20\amos\projectmanagement\models\ProjectsTasks::className());
            $this->setMmStartKey('projects_tasks_id');
            $this->setTargetObjClassName($this->organizzazioniModule->model('Profilo'));
            $this->setMmTargetKey('organization_id');
            $this->setRedirectAction('/project_management/projects-tasks/update');
            $this->setOptions(['#' => 'tab-organizations']);
            $this->setTargetUrl('associa_organizations_to_project_task_m2m');
            $this->setCustomQuery(true);
            $this->setModuleClassName(Module::className());
            $this->setRedirectArray('/project_management/projects-tasks/update?id=' . $projectTaskId . '#tab-organizations');
            return $this->actionAssociaM2m($projectTaskId);
        } else {
            throw new \Exception(Module::t('organizations', 'The module project is not enabled'));
        }
    }

    /**
     * @param int $userId
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociateOrganizationM2mQuery($userId)
    {
        /** @var Profilo $organization */
        $organization = $this->organizzazioniModule->createModel('Profilo');
        /** @var ProfiloUserMm $modelProfiloUserMm */
        $modelProfiloUserMm = $this->organizzazioniModule->createModel('ProfiloUserMm');
        $profiloTable = $organization::tableName();
        $profiloUserMmTable = $modelProfiloUserMm::tableName();

        /** @var ActiveQuery $queryAssociated */
        $queryAssociated = $modelProfiloUserMm::find();
        $queryAssociated->select([$profiloUserMmTable . '.profilo_id']);
        $queryAssociated->andWhere([$profiloUserMmTable . '.user_id' => $userId]);
        $alreadyAssociatedOrganizationIds = $queryAssociated->column();

        $query = $organization->getUserNetworkAssociationQuery($userId);
        if ($this->organizzazioniModule->enableWorkflow) {
            $query->andWhere([$profiloTable . '.status' => $organization->getValidatedStatus()]);
        }
        $query->andWhere(['not in', $profiloTable . '.id', $alreadyAssociatedOrganizationIds]);

        $post = Yii::$app->request->post();
        if (isset($post['genericSearch'])) {
            $query->andFilterWhere(['like', $profiloTable . '.name', $post['genericSearch']]);
        }

        return $query;
    }

    /**
     * This method returns the columns showed in the associate organization m2m action,
     * which is the one the user can reach from his profile in the network tab.
     * @param int $userId
     * @return array
     */
    public function getAssociateOrganizationM2mTargetColumns($userId)
    {
        return [
            'logo' => [
                'headerOptions' => [
                    'id' => Module::t('amosorganizzazioni', '#logo'),
                ],
                'contentOptions' => [
                    'headers' => Module::t('amosorganizzazioni', '#logo'),
                ],
                'label' => Module::t('amosorganizzazioni', '#logo'),
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var Profilo $model */
                    return ProfiloCardWidget::widget(['model' => $model]);
                }
            ],
            'name',
            'created_by' => [
                'attribute' => 'created_by',
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Profilo $model */
                    $name = '-';
                    if (!is_null($model->createdUserProfile)) {
                        return $model->createdUserProfile->getNomeCognome();
                    }
                    return $name;
                }
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{info}{view}{joinOrganization}',
                'buttons' => [
                    'joinOrganization' => function ($url, $model) use ($userId) {
                        /** @var Profilo $model */
                        $btn = JoinProfiloWidget::widget(['model' => $model, 'userId' => $userId, 'isGridView' => true]);
                        return $btn;
                    }
                ]
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function actionAssociateOrganizationM2m()
    {
        /**
         * Questo è uno user profile id. Verificare nel widget UserNetworkWidgetOrganizzazioni
         * dove viene configurato l'M2MWidget il model che gli viene passato (è uno UserProfile).
         * Verificare in M2MWidget il metodo renderToolbarMittente per capire come viene composto il link dell'associa btn.
         */
        $userProfileId = Yii::$app->request->get('id');
        Url::remember();

        $this->setMmTableName($this->organizzazioniModule->createModel('ProfiloUserMm')->className());
        $this->setStartObjClassName(User::className());
        $this->setMmStartKey('user_id');
        $this->setTargetObjClassName($this->organizzazioniModule->model('Profilo'));
        $this->setMmTargetKey('profilo_id');
        $this->setRedirectAction('update');
        $this->setTargetUrl('associate-organization-m2m');
        $this->setCustomQuery(true);
        $this->setRedirectArray('/' . AmosAdmin::getModuleName() . '/user-profile/update?id=' . $userProfileId . '#tab-network');
        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
        $userProfile = $userProfileModel::findOne($userProfileId);
        if (!is_null($userProfile)) {
            if (Yii::$app->user->can('ASSOCIATE_ORGANIZZAZIONI_TO_USER', ['model' => $userProfile])) {
                return $this->actionAssociaM2m($userProfile->user_id);
            } else {
                throw new ForbiddenHttpException(Yii::t('amoscore', 'Non sei autorizzato a visualizzare questa pagina'));
            }
        } else {
            Yii::$app->getSession()->addFlash(
                'danger',
                Module::t('amosorganizzazioni', '#error_associate_organization_m2m_userprofile_not_found')
            );
        }

        return $this->actionAnnullaM2m($userProfile->user_id);
    }

    /**
     * @param int $organizationId
     * @param bool $accept
     * @param array|string|null $redirectAction
     * @return \yii\web\Response
     * @throws \ReflectionException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionJoinOrganization($organizationId, $accept = false, $redirectAction = null)
    {
        $defaultAction = 'index';
        $ok = false;

        if (empty($redirectAction)) {
            $urlPrevious = Url::previous();
            $redirectAction = $urlPrevious;
        }
        if (!$organizationId) {
            Yii::$app->getSession()->addFlash(
                'danger',
                Module::tHtml(
                    'amosorganizzazioni',
                    "It is not possible to subscribe the user. Missing parameter organization."
                )
            );
            return $this->redirect($defaultAction);
        }

        $nomeCognome = ' ';
        $organizationName = '';
        $userId = Yii::$app->request->get('userId');
        if (isset($userId) && ($userId > 0)) {
            $user = User::findOne($userId);
        } else {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $userId = $user->id;
        }
        $userProfile = $user->userProfile;
        if (!is_null($userProfile)) {
            $nomeCognome = "'" . $userProfile->nomeCognome . "'";
        }

        /** @var Profilo $profiloModel */
        $profiloModel = $this->organizzazioniModule->createModel('Profilo');
        $organization = $profiloModel::findOne($organizationId);
        if (!is_null($organization)) {
            $organizationName = "'" . $organization->name . "'";
            if ($this->organizzazioniModule->enableWorkflow && ($organization->status != $organization->getValidatedStatus())) {
                Yii::$app->getSession()->addFlash(
                    'danger',
                    Module::tHtml(
                        'amosorganizzazioni',
                        '#join_organization_not_validated_organization',
                        [
                            'organizationName' => $organizationName
                        ]
                    )
                );
                $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
                return $this->redirect($action);
            }
        } else {
            Yii::$app->getSession()->addFlash(
                'danger',
                Module::tHtml('amosorganizzazioni', '#join_organization_not_found_organization')
            );
            $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
            return $this->redirect($action);
        }

        /** @var ProfiloUserMm $profiloUserMm */
        $profiloUserMm = $this->organizzazioniModule->createModel('ProfiloUserMm');
        $userOrganization = $profiloUserMm::findOne(['profilo_id' => $organizationId, 'user_id' => $userId]);

        // Verify if the user is already in the organization user relation table
        if (is_null($userOrganization)) {
            $organizationRefereesIds = OrganizzazioniUtility::getOrganizationReferees($organizationId, true);
            if (in_array($userId, $organizationRefereesIds)) {
                // The user is a legal representative or a operative referee for the organization, then cannot be a member now.
                // In future modify this code if you want to enable the roles in MM table like communities (and remove this comment).
                Yii::$app->getSession()->addFlash(
                    'danger',
                    Module::tHtml(
                        'amosorganizzazioni',
                        '#join_organization_already_referee',
                        [
                            'nomeCognome' => $nomeCognome,
                            'organizationName' => $organizationName
                        ]
                    )
                );
                $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
                return $this->redirect($action);
            } else {
                // Iscrivo l'utente all'organizzazione
                /** @var ProfiloUserMm $userOrganization */
                $userOrganization = $this->organizzazioniModule->createModel('ProfiloUserMm');
                $userOrganization->profilo_id = $organizationId;
                $userOrganization->user_id = $userId;
                if (!$this->organizzazioniModule->enableConfirmUsersJoinRequests) {
                    // If the confirm of an user that request to join an organization is disabled set directly the active status and do anything else.
                    $userOrganization->status = ProfiloUserMm::STATUS_ACTIVE;
                    $message = Module::tHtml(
                            'amosorganizzazioni',
                            "You are now linked to the organization"
                        ) . ' ' . $organizationName;
                    if ($this->forceSendWelcomeInJoinOrganization) {
                        $emailUtil = new EmailUtility(
                            EmailUtility::WELCOME,
                            $userOrganization->role,
                            $organization,
                            $userProfile->nomeCognome,
                            '',
                            null,
                            $userProfile->user_id
                        );
                        $subject = $emailUtil->getSubject();
                        $text = $emailUtil->getText();
                        $ok = $emailUtil->sendMail(null, $userProfile->user->email, $subject, $text, [], []);
                        if (!$ok) {
                            Yii::getLogger()->log('Error sending welcome mail', Logger::LEVEL_ERROR);
                        }
                    }
                } else {
                    // If the confirm of an user that request to join an organization is enabled set the request confirm status and send an email to the legal representative.
                    $userOrganization->status = ProfiloUserMm::STATUS_WAITING_REQUEST_CONFIRM;
                    $message = Module::tHtml(
                        'amosorganizzazioni',
                        '#join_organization_request_forwarded_to_referees',
                        [
                            'organizationName' => $organizationName
                        ]
                    );
                    $emailUtil = new EmailUtility(
                        EmailUtility::REGISTRATION_REQUEST,
                        $userOrganization->role,
                        $organization,
                        $userProfile->nomeCognome,
                        '',
                        null,
                        $userProfile->user_id
                    );
                    $organizationRefereesEmails = $emailUtil->getOrganizationRefereesMailList($organizationId);
                    $subject = $emailUtil->getSubject();
                    $text = $emailUtil->getText();
                    foreach ($organizationRefereesEmails as $to) {
                        $emailUtil->sendMail(null, $to, $subject, $text, [], []);
                    }
                }
                $ok = $userOrganization->save(false);

                if ($ok) {
                    $organization->setCwhAuthAssignments($userOrganization);
                    Yii::$app->getSession()->addFlash('success', $message);
                    if (strpos($redirectAction, 'associate-organization-m2m') && !Yii::$app->user->can(
                            'ASSOCIATE_ORGANIZZAZIONI_TO_USER',
                            ['model' => $userProfile]
                        )) {
                        $redirectAction = '/' . AmosAdmin::getModuleName() . '/user-profile/update?id=' . $userProfile->id . '#tab-network';
                    }
                    $action = (isset($redirectAction) ? $redirectAction : $defaultAction);
                    return $this->redirect($action);
                } else {
                    Yii::$app->getSession()->addFlash(
                        'danger',
                        Module::tHtml(
                            'amosorganizzazioni',
                            '#join_organization_error',
                            [
                                'nomeCognome' => $nomeCognome,
                                'organizationName' => $organizationName
                            ]
                        )
                    );
                    return $this->redirect($defaultAction);
                }
            }
        } else {
            if ($userOrganization->status == ProfiloUserMm::STATUS_WAITING_OK_USER) { // User has been invited and decide to accept or reject
                $profilo = $userOrganization->profilo;
                $invitedByUser = User::findOne(['id' => $userOrganization->created_by]);
                if ($accept) {
                    $message = Module::tHtml(
                        'amosorganizzazioni',
                        "#join_organization_user_accept",
                        ['organizationName' => $profilo->name]
                    );
                    $userOrganization->status = ProfiloUserMm::STATUS_ACTIVE;
                    $ok = $userOrganization->save(false);

                    // Email to organization referees
                    $emailUtilToManager = new EmailUtility(
                        EmailUtility::ACCEPT_INVITATION,
                        $userOrganization->role,
                        $organization,
                        $userProfile->nomeCognome,
                        '',
                        null,
                        $userProfile->user_id
                    );
                    $subjectToManager = $emailUtilToManager->getSubject();
                    $textToManager = $emailUtilToManager->getText();
                    $emailUtilToManager->sendMail(null, $invitedByUser->email, $subjectToManager, $textToManager, [], []);

                    // Email to new organization member
                    $emailUtilToUser = new EmailUtility(
                        EmailUtility::WELCOME,
                        $userOrganization->role,
                        $organization,
                        $userProfile->nomeCognome,
                        '',
                        null,
                        $userProfile->user_id
                    );
                    $subjectToUser = $emailUtilToUser->getSubject();
                    $textToUser = $emailUtilToUser->getText();
                    $emailUtilToUser->sendMail(null, $user->email, $subjectToUser, $textToUser, [], []);
                } else {
                    $message = Module::tHtml(
                        'amosorganizzazioni',
                        "#join_organization_user_reject",
                        ['organizationName' => $profilo->name]
                    );
                    $emailUtil = new EmailUtility(
                        EmailUtility::REJECT_INVITATION,
                        $userOrganization->role,
                        $organization,
                        $userProfile->nomeCognome,
                        '',
                        null,
                        $userProfile->user_id
                    );
                    $subject = $emailUtil->getSubject();
                    $text = $emailUtil->getText();
                    $userOrganization->status = ProfiloUserMm::STATUS_REJECTED;
                    $userOrganization->save(false);
                    $userOrganization->delete();
                    $ok = !$userOrganization->hasErrors();
                    $emailUtil->sendMail(null, $invitedByUser->email, $subject, $text, [], []);
                }
            } elseif ($userOrganization->status == ProfiloUserMm::STATUS_ACTIVE) {
                $this->addFlash(
                    'info',
                    Module::tHtml(
                        'amosorganizzazioni',
                        '#join_organization_user_already_joined',
                        [
                            'nomeCognome' => $nomeCognome,
                            'organizationName' => $organizationName
                        ]
                    )
                );
            } elseif ($userOrganization->status == ProfiloUserMm::STATUS_REJECTED) {
                $this->addFlash(
                    'info',
                    Module::tHtml(
                        'amosorganizzazioni',
                        '#join_organization_user_rejected',
                        [
                            'nomeCognome' => $nomeCognome,
                            'organizationName' => $organizationName
                        ]
                    )
                );
            } else {
                $this->addFlash(
                    'info',
                    Module::tHtml(
                        'amosorganizzazioni',
                        '#join_organization_user_already_joined',
                        [
                            'nomeCognome' => $nomeCognome,
                            'organizationName' => $organizationName
                        ]
                    )
                );
            }

            if ($ok) {
                $this->addFlash('success', $message);
                if (isset($redirectAction)) {
                    return $this->redirect($redirectAction);
                } else {
                    return $this->redirect($defaultAction);
                }
            } else {
                $this->addFlash(
                    'danger',
                    Module::tHtml('amosorganizzazioni', "Error occured while subscribing the user") . $nomeCognome . Module::tHtml(
                        'amosorganizzazioni',
                        "to community"
                    ) . $organizationName
                );
                return $this->redirect($defaultAction);
            }
        }
    }

    /**
     * Organization referees accepts the user membership request to an organization
     *
     * @param $profiloId
     * @param $userId
     * @return \yii\web\Response
     */
    public function actionAcceptUser($profiloId, $userId)
    {
        return $this->redirect($this->acceptOrRejectUser($profiloId, $userId, true));
    }

    /**
     * Organization referees rejects the user membership request to an organization
     *
     * @param int $profiloId
     * @param int $userId
     * @return \yii\web\Response
     */
    public function actionRejectUser($profiloId, $userId)
    {
        return $this->redirect($this->acceptOrRejectUser($profiloId, $userId, false));
    }

    /**
     * @param int $profiloId
     * @param int $userId
     * @param bool $acccept - true if User membership request has been accepted by organization referees, false if rejected
     * @return string
     */
    private function acceptOrRejectUser($profiloId, $userId, $acccept)
    {
        /** @var ProfiloUserMm $profiloUserMm */
        $profiloUserMm = $this->organizzazioniModule->createModel('ProfiloUserMm');
        /** @var ProfiloUserMm $userOrganization */
        $userOrganization = $profiloUserMm::findOne(['profilo_id' => $profiloId, 'user_id' => $userId]);
        $redirectUrl = '';

        if (!is_null($userOrganization)) {
            $nomeCognome = " ";
            $organizationName = '';
            $redirectUrl = Url::previous();

            $user = User::findOne($userId);
            $userProfile = $user->userProfile;
            if (!is_null($userProfile)) {
                $nomeCognome = "'" . $userProfile->nomeCognome . "'";
            }

            /** @var Profilo $profiloModel */
            $profiloModel = $this->organizzazioniModule->createModel('Profilo');
            $organization = $profiloModel::findOne($profiloId);
            if (!is_null($organization)) {
                $organizationName = "'" . $organization->name . "'";
            }

            if ($acccept) {
                $retVal = $this->welcomeUserOperations($organization, $userOrganization);
            } else {
                $retVal = $this->rejectUserOperations($organization, $userOrganization);
            }

            $emailUtil = new EmailUtility(
                $retVal['emailType'],
                $userOrganization->role,
                $organization,
                $userProfile->nomeCognome,
                $retVal['refereeName'],
                null,
                $userProfile->user_id
            );
            $subject = $emailUtil->getSubject();
            $text = $emailUtil->getText();
            $emailUtil->sendMail(null, $user->email, $subject, $text, [], []);

            $message = Module::tHtml(
                'amosorganizzazioni',
                $retVal['messagePlaceholder'],
                [
                    'nomeCognome' => $nomeCognome,
                    'organizationName' => $organizationName
                ]
            );
            $this->addFlash('success', $message);
        }
        return $redirectUrl;
    }

    /**
     * Operations when the user accept the invitation.
     * @param Profilo $organization
     * @param ProfiloUserMm $userOrganization
     * @return array
     */
    protected function welcomeUserOperations($organization, $userOrganization)
    {
        $userOrganization->status = $userOrganization::STATUS_ACTIVE;
        $userOrganization->save(false);
        $organization->setCwhAuthAssignments($userOrganization);
        return [
            'emailType' => EmailUtility::WELCOME,
            'messagePlaceholder' => '#join_organization_user_accepted',
            'refereeName' => ''
        ];
    }

    /**
     * Operations when the user reject the invitation.
     * @param Profilo $organization
     * @param ProfiloUserMm $userOrganization
     * @return array
     */
    protected function rejectUserOperations($organization, $userOrganization)
    {
        $userOrganization->status = $userOrganization::STATUS_REJECTED;
        $userOrganization->save(false);
        $userOrganization->delete();
        return [
            'emailType' => EmailUtility::REGISTRATION_REJECTED,
            'messagePlaceholder' => '#join_organization_user_rejected_successfully',
            'refereeName' => Yii::$app->user->identity->userProfile->getNomeCognome()
        ];
    }

    /**
     * @param int $userId
     * @param bool $isUpdate
     * @return string
     */
    public function actionUserNetwork($userId, $isUpdate = false)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);

            return $this->render(
                'user-network',
                [
                    'userId' => $userId,
                    'isUpdate' => $isUpdate
                ]
            );
        }
        return '';
    }

    /**
     * Employees of an organization m2m widget - Ajax call to redraw the widget
     *
     * @param int $id
     * @param string $classname
     * @param array $params
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionOrganizationEmployees($id, $classname, array $params)
    {

        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);

            /** @var Record $object */
            $object = \Yii::createObject($classname);
            $model = $object->findOne($id);
            $showAdditionalAssociateButton = $params['showAdditionalAssociateButton'];
            $viewEmail = $params['viewEmail'];
            $checkManagerRole = $params['checkManagerRole'];
            $addPermission = $params['addPermission'];
            $manageAttributesPermission = $params['manageAttributesPermission'];
            $forceActionColumns = $params['forceActionColumns'];
            $actionColumnsTemplate = $params['actionColumnsTemplate'];
            $viewM2MWidgetGenericSearch = $params['viewM2MWidgetGenericSearch'];
            $enableModal = $params['enableModal'];
            $gridId = $params['gridId'];
            $organizationManagerRoleName = $params['organizationManagerRoleName'];
            $redirectUrlAfterAssociate = $params['redirectUrlAfterAssociate'];
            $filterForRole = $params['filterForRole'];
            $userStatusAssociate = $params['userStatusAssociate'];
            $isUpdate = $params['isUpdate'];
            $isUpdate = (strpos(\Yii::$app->request->referrer, 'profilo/update') !== false) ? true : $isUpdate;
            return $this->render(
                'organization-employees',
                [
                    'model' => $model,
                    'showRoles' => isset($params['showRoles']) ? $params['showRoles'] : [],
                    'showAdditionalAssociateButton' => $showAdditionalAssociateButton,
                    'additionalColumns' => isset($params['additionalColumns']) ? $params['additionalColumns'] : [],
                    'viewEmail' => $viewEmail,
                    'checkManagerRole' => $checkManagerRole,
                    'addPermission' => $addPermission,
                    'manageAttributesPermission' => $manageAttributesPermission,
                    'forceActionColumns' => $forceActionColumns,
                    'actionColumnsTemplate' => $actionColumnsTemplate,
                    'viewM2MWidgetGenericSearch' => $viewM2MWidgetGenericSearch,
                    'targetUrlParams' => isset($params['targetUrlParams']) ? $params['targetUrlParams'] : [],
                    'enableModal' => $enableModal,
                    'gridId' => $gridId,
                    'organizationManagerRoleName' => $organizationManagerRoleName,
                    'redirectUrlAfterAssociate' => $redirectUrlAfterAssociate,
                    'filterForRole' => $filterForRole,
                    'userStatusAssociate' => $userStatusAssociate,
                    'isUpdate' => $isUpdate

            ]
            );
        }
        return null;
    }

    /**
     * @param int $profiloId
     * @param int $userId
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionChangeUserRoleArea($profiloId, $userId)
    {
        /** @var ProfiloUserMm $profiloUserMm */
        $profiloUserMm = $this->organizzazioniModule->createModel('ProfiloUserMm');
        $profiloUserMm = $profiloUserMm::findOne(['profilo_id' => $profiloId, 'user_id' => $userId]);
        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
        /** @var UserProfile $userProfile */
        $userProfile = $userProfileModel::findOne(['user_id' => $userId]);
        $this->model = $this->findModel($profiloId);

        if (Yii::$app->user->can('USERPROFILE_UPDATE', ['model' => $userProfile]) || Yii::$app->user->can('ADMIN') || Yii::$app->user->can('AMMINISTRATORE_ORGANIZZAZIONI')) {
            if (Yii::$app->getRequest()->isAjax && Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                if (!is_null($profiloUserMm) && isset($post['user_profile_role']) && isset($post['user_profile_area'])) {
                    $profiloUserMm->user_profile_role_id = $post['user_profile_role'];
                    $profiloUserMm->user_profile_area_id = $post['user_profile_area'];
                    $ok = $profiloUserMm->save(false);
                    if ($ok) {
                        $profiloName = '';
                        if (!is_null($this->model)) {
                            $profiloName = $this->model->name;
                        }
                        $message = "'" . $userProfile->nomeCognome . "' " . Module::tHtml('amosorganizzazioni', 'is now') .
                            " " . $profiloUserMm->userProfileRole->name . " " .
                            Module::tHtml('amosorganizzazioni', 'of') . " '" . $profiloName . "'";
                        $this->addFlash('success', $message);
                    }
                }
            }
        } else {
            $this->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
        }
    }

    /**
     * @param int|null $id Profilo Organizzazione id
     * @return \yii\web\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCreateCommunity($id = null)
    {
        Url::remember();

        /** @var Profilo $model */
        $model = $this->findModel($id);
        $model->createCommunityOrganizzazione($this->module->communityType);

        return $this->redirect($model->getFullViewUrl());
    }

    /**
     * @param string|null $currentView
     * @return string
     */
    public function actionProfiloToPublish($currentView = null)
    {
        if (!$this->organizzazioniModule->enableWorkflow) {
            return $this->redirect(['/organizzazioni/profilo/index']);
        }
        $this->setDataProvider($this->getModelSearch()->searchToValidateProfilo(Yii::$app->request->getQueryParams()));
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', Module::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);
        //$this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->setCurrentView($this->getAvailableView('grid'));
        return $this->baseListsAction(Module::t('amosorganizzazioni', 'To Validate'));
    }

    /**
     * Base operations for list views
     * @param string $pageTitle
     * @return string
     */
    protected function baseListsAction($pageTitle)
    {
        Url::remember();
        $this->setTitleAndBreadcrumbs($pageTitle);
        $this->setListViewsParams();
        $renderParams = [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ];
        return $this->render('index', $renderParams);
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $organizzazioniPageTitle
     */
    protected function setTitleAndBreadcrumbs($organizzazioniPageTitle)
    {
        $this->setNetworkDashboardBreadcrumb();
        Yii::$app->session->set('previousTitle', $organizzazioniPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $organizzazioniPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $organizzazioniPageTitle];
    }

    public function setNetworkDashboardBreadcrumb()
    {
        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        $scope = NULL;
        if (!empty($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
        }
        if (!empty($scope)) {
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
                $community = \open20\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = Module::t('amosorganizzazioni', "Dashboard") . ' ' . $community->name;
                $dasbboardCommunityUrl = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = ['label' => $dashboardCommunityTitle, 'url' => $dasbboardCommunityUrl];
            }
        }
    }

    protected function setListViewsParams()
    {
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
        Yii::$app->session->set(Module::beginCreateNewSessionKey(), Url::previous());
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => Module::t('amosorganizzazioni', '#add_organization'),
            'urlCreateNew' => '/organizzazioni/profilo/create'
        ];
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRejectProfilo($id)
    {
        if (!$this->organizzazioniModule->enableWorkflow) {
            Yii::$app->session->addFlash('danger', Module::t('amosorganizzazioni', '#workflow_disabled'));
            return $this->redirect(Url::previous());
        }
        $this->model = $this->findModel($id);
        try {
            $this->model->sendToStatus(Profilo::PROFILO_WORKFLOW_STATUS_DRAFT);
            $ok = $this->model->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', Module::t('amosorganizzazioni', 'Organizzazione respita!'));
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    Module::t('amosorganizzazioni', 'Errore durante il rifiuto dell\'organizzazione')
                );
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }

        return $this->redirect(Url::previous());
    }

    /**
     *
     */
    public function actionMyOrganizations()
    {
        Url::remember();
        Yii::$app->view->params['textHelp']['filename'] = 'organizzazioni_dashboard_description';
        $this->setDataProvider($this->modelSearch->searchMyOrganizations(Yii::$app->request->getQueryParams()));


        if ($this->organizzazioniModule->enableManageLinks) {
            $labelLinkAll = Module::t('amosorganizzazioni', 'Tutte le organizzazioni');
            $urlLinkAll = '/organizzazioni/profilo/index';
            Yii::$app->view->params['labelLinkAll'] = $labelLinkAll;
            Yii::$app->view->params['urlLinkAll'] = $urlLinkAll;
        }

        \Yii::$app->controller->view->params['titleSection'] = Module::t('amosorganizzazioni', 'Le mie organizzazioni');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        $this->setImportButton();


        $this->setUpLayout('list');
        return $this->render(
            'index',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null,
                'moduleName' => ($this->moduleName) ? $this->moduleName : null,
                'contextModelId' => ($this->contextModelId) ? $this->contextModelId : null,
            ]
        );
    }


    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionValidateProfilo($id)
    {
        if (!$this->organizzazioniModule->enableWorkflow) {
            Yii::$app->session->addFlash('danger', Module::t('amosorganizzazioni', '#workflow_disabled'));
            return $this->redirect(Url::previous());
        }
        $this->model = $this->findModel($id);
        try {
            $this->model->sendToStatus(Profilo::PROFILO_WORKFLOW_STATUS_VALIDATED);

            $ok = $this->model->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', Module::t('amosorganizzazioni', 'Organizzazione validata!'));
            } else {
                Yii::$app->session->addFlash(
                    'danger',
                    Module::t('amosorganizzazioni', 'Errore durante la validzione dell\'organizzazione')
                );
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

        $module = \Yii::$app->getModule('organizzazioni');
        if ($module && $module->enableManageLinks) {
            if (!\Yii::$app->user->isGuest) {
                $links[] = [
                    'title' => Module::t('amosorganizzazioni', 'Visualizza tutte le mie organizzazioni'),
                    'label' => Module::t('amosorganizzazioni', 'Le mie organizzazioni'),
                    'url' => '/organizzazioni/profilo/my-organizations'
                ];
            }

            $links[] = [
                'title' => Module::t('amosorganizzazioni', 'Visualizza tutte le organizzazioni'),
                'label' => Module::t('amosorganizzazioni', 'Tutte'),
                'url' => '/organizzazioni/profilo/index'
            ];
        }

        return $links;
    }

}
