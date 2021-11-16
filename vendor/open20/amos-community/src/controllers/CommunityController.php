<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

namespace open20\amos\community\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\models\RegisterForm;
use open20\amos\community\rbac\UpdateOwnCommunityProfile;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\community\utilities\EmailUtil;
use open20\amos\community\widgets\icons\WidgetIconAdminAllCommunity;
use open20\amos\community\widgets\icons\WidgetIconCommunity;
use open20\amos\community\widgets\icons\WidgetIconCreatedByCommunities;
use open20\amos\community\widgets\icons\WidgetIconMyCommunities;
use open20\amos\community\widgets\icons\WidgetIconMyCommunitiesWithTags;
use open20\amos\community\widgets\icons\WidgetIconToValidateCommunities;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\core\utilities\SpreadSheetFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class CommunityController
 * This is the class for controller "CommunityController".
 * @package open20\amos\community\controllers
 *
 * @property \open20\amos\community\models\Community $model
 */
class CommunityController extends \open20\amos\community\controllers\base\CommunityController
{

    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setMmTableName(CommunityUserMm::className());
        $this->setStartObjClassName(Community::className());
        $this->setMmStartKey('community_id');
        $this->setTargetObjClassName(User::className());
        $this->setMmTargetKey('user_id');
        $this->setRedirectAction('update');
        $this->setOptions(['tabActive' => 'tab-participants']);
        if (AmosCommunity::instance()->customInvitationForm) {
            $this->setTargetUrl('insass-m2m');
        } else {
            $this->setTargetUrl('associa-m2m');
        }
        $this->setAdditionalTargetUrl('additional-associate-m2m');
        
        $targetUrl = '/invitations/invitation/index' . (\Yii::$app->user->can('INVITATIONS_ADMINISTRATOR') ? '-all' : '') . '/';
        $this->setTargetUrlInvitation($targetUrl);
        $this->setInvitationModule(AmosCommunity::getModuleName());
        
        $this->setM2mAttributesManageViewPath('manage-m2m-attributes');
        $this->setCustomQuery(true);
        $this->setMmTableAttributesDefault([
            'status' => CommunityUserMm::STATUS_INVITE_IN_PROGRESS,
            'role' => CommunityUserMm::ROLE_PARTICIPANT
        ]);
        $this->setUpLayout('main');
        $this->setModuleClassName(AmosCommunity::className());
        $this->on(M2MEventsEnum::EVENT_BEFORE_ASSOCIATE_M2M, [$this, 'beforeAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_CANCEL_ASSOCIATE_M2M, [$this, 'beforeCancelAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_ASSOCIATE_M2M, [$this, 'afterAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_DELETE_M2M, [$this, 'beforeDeleteM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_DELETE_M2M, [$this, 'afterDeleteM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_MANAGE_ATTRIBUTES_M2M, [$this, 'afterManageAttributesM2m']);
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
                                'associate-community-m2m',
                            ],
                            'roles' => [UpdateOwnCommunityProfile::className()]
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'my-communities',
                                'own-interest-communities',
                                'join-community',
                                'index',
                                'user-network',
                                'community-members',
                                'community-members-min',
                                'increment-community-hits',
                                'participants'
                            ],
                            'roles' => ['COMMUNITY_READER', 'COMMUNITY_MEMBER', 'AMMINISTRATORE_COMMUNITY', 'BASIC_USER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'created-by-communities',
                                'closing',
                                'accept-user',
                                'reject-user',
                                'change-user-role',
                                'associa-m2m',
                                'insass-m2m',
                                'additional-associate-m2m',
                                'annulla-m2m',
                                'elimina-m2m',
                                'manage-m2m-attributes'
                            ],
                            'roles' => ['AMMINISTRATORE_COMMUNITY', 'COMMUNITY_CREATOR', 'COMMUNITY_MEMBER']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'to-validate-communities',
                                'publish',
                                'reject'
                            ],
                            'roles' => ['COMMUNITY_VALIDATOR', 'COMMUNITY_CREATOR', 'COMMUNITY_UPDATE']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'admin-all-communities',
                            ],
                            'roles' => ['AMMINISTRATORE_COMMUNITY']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'confirm-manager',
                                'deleted-community'
                            ],
                            'roles' => ['@']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'user-joined-report-download'
                            ],
                            'roles' => ['ADMIN']
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
     * @param $event
     */
    public function beforeAssociateM2m($event)
    {
        $moduleCommunity             = Yii::$app->getModule('community');
        $inviteUserOfcommunityParent = $moduleCommunity->inviteUserOfcommunityParent;
        if ($inviteUserOfcommunityParent) {
            $id    = Yii::$app->request->get('id');
            $model = $this->findModel($id);
            if ($model->parent_id) {
                $this->setTargetUrl('associa-m2m');
            }
        }
    }

    /**
     * @param $event
     */
    public function afterAssociateM2m($event)
    {
        $urlPrevious = Url::previous();
        if (!strstr($urlPrevious, 'associate-community-m2m')) {
            $communityId  = Yii::$app->request->get('id');
            $userStatus   = Yii::$app->request->get('userStatus');
            /** @var Community $community */
            $community    = Community::findOne($communityId);
            $callingModel = Yii::createObject($community->context);
            $redirectUrl  = $this->getRedirectUrl($callingModel, $communityId);

            $loggedUser        = User::findOne(Yii::$app->getUser()->id);
            /** @var UserProfile $loggedUserProfile */
            $loggedUserProfile = $loggedUser->getProfile();

            $userCommunityRows = CommunityUserMm::find()->andWhere([
                    'status' => CommunityUserMm::STATUS_INVITE_IN_PROGRESS,
                    'community_id' => $communityId
                ])->all();
            foreach ($userCommunityRows as $userCommunity) {
                /** @var CommunityUserMm $userCommunity */
                $userCommunity->status = (!is_null($userStatus) ? $userStatus : CommunityUserMm::STATUS_WAITING_OK_USER);
                $userCommunity->role   = $callingModel->getBaseRole();
                $userCommunity->save(false);

                /** @var User $userToInvite */
                $userToInvite        = User::findOne($userCommunity->user_id);
                /** @var UserProfile $userToInviteProfile */
                $userToInviteProfile = $userToInvite->getProfile();
                $emailUtil           = new EmailUtil(EmailUtil::INVITATION, $userCommunity->role, $community,
                    $userToInviteProfile->nomeCognome, $loggedUserProfile->getNomeCognome(), null,
                    $userToInviteProfile->user_id);
                $subject             = $emailUtil->getSubject();
                $text                = $emailUtil->getText();
                $this->sendMail(null, $userToInvite->email, $subject, $text, [], []);
            }

            $this->setRedirectArray($redirectUrl.'#tab-participants');
        }
    }

    /**
     * @param $event
     */
    public function afterManageAttributesM2m($event)
    {

        $userCommunityId = Yii::$app->request->get('targetId');
        $userCommunity   = CommunityUserMm::findOne($userCommunityId);

        $communityId  = $userCommunity->community_id;
        $community    = Community::findOne($communityId);
        $callingModel = Yii::createObject($community->context);
        $redirectUrl  = $this->getRedirectUrl($callingModel, $communityId);

        if (!is_null($userCommunity)) {
            $nomeCognome   = "";
            $communityName = '';

            /** @var UserProfile $userProfile */
            $user        = User::findOne($userCommunity->user_id);
            $userProfile = $user->getProfile();
            if (!is_null($userProfile)) {
                $nomeCognome = "'".$userProfile->nomeCognome."'";
            }
            if (!is_null($community)) {
                $communityName = " '".$community->name."'";
            }
            $message = $nomeCognome . " " . AmosCommunity::t('amoscommunity', "is now") .
                " '" . AmosCommunity::t('amoscommunity', $userCommunity->role) . "' " .
                AmosCommunity::t('amoscommunity', "of") . $communityName;
            $community->setCwhAuthAssignments($userCommunity);
            $emailUtil = new EmailUtil(EmailUtil::CHANGE_ROLE, $userCommunity->role, $community,
                $userProfile->nomeCognome, '', null, $userProfile->user_id);
            $subject   = $emailUtil->getSubject();
            $text      = $emailUtil->getText();
            $this->sendMail(null, $user->email, $subject, $text, [], []);
            $this->addFlash('success', $message);
        }

        $this->setRedirectArray($redirectUrl.'#tab-participants');
    }

    /**
     * @param $event
     */
    public function beforeDeleteM2m($event)
    {
        $communityId        = Yii::$app->request->get('id');
        $userId             = Yii::$app->request->get('targetId');
        /** @var Community $community */
        $community          = Community::findOne($communityId);
        $communityUserMmRow = CommunityUserMm::findOne(['community_id' => $communityId, 'user_id' => $userId]);
        //remove all cwh permissions for domain = community
        $community->setCwhAuthAssignments($communityUserMmRow, true);
    }

    /**
     * @param $event
     */
    public function afterDeleteM2m($event)
    {
        $this->setRedirectArray([Url::previous()]);
    }

    /**
     * @return mixed
     */
    public function actionAssociateCommunityM2m()
    {
        $userId = Yii::$app->request->get('id');
        Url::remember();

        $this->setMmTableName(CommunityUserMm::className());
        $this->setStartObjClassName(User::className());
        $this->setMmStartKey('user_id');
        $this->setTargetObjClassName(Community::className());
        $this->setMmTargetKey('community_id');
        $this->setRedirectAction('update');
        $this->setTargetUrl('associate-community-m2m');

        $userProfileId = User::findOne($userId)->getProfile()->id;
        $this->setRedirectArray('/admin/user-profile/update?id='.$userProfileId.'#tab-network');
        return $this->actionAssociaM2m($userId);
    }

    /**
     * @param $event
     */
    public function beforeCancelAssociateM2m($event)
    {
        $urlPrevious = Url::previous();
        $id          = Yii::$app->request->get('id');
        if (!strstr($urlPrevious, 'associate-community-m2m')) {
            /** @var Community $community */
            $community    = Community::findOne($id);
            $callingModel = Yii::createObject($community->context);

            $redirectUrl = $this->getRedirectUrl($callingModel, $id);

            $this->setRedirectArray($redirectUrl.'#tab-participants');
        } else {
            $this->setRedirectArray('/admin/user-profile/update?id='.$id);
        }
    }

    /**
     * @param $callingModel
     * @param $communityId
     * @return string $redirectUrl
     */
    private function getRedirectUrl($callingModel, $communityId)
    {
        $redirectId = $communityId;
        if (!is_a($callingModel, Community::className())) {
            $this->setOptions(null);
            $callee     = $callingModel->findOne(['community_id' => $communityId]);
            $redirectId = $callee->id;
        }
        $createRedirectUrlParams = [
            $callingModel->getPluginModule().'/'.$callingModel->getPluginController().'/'.$callingModel->getRedirectAction(),
            'id' => $redirectId,
        ];
        $redirectUrl             = Yii::$app->urlManager->createUrl($createRedirectUrlParams);

        return $redirectUrl;
    }

    /**
     * Method to view all communities
     *
     * @return string
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        Yii::$app->session->set('previousUrl', Url::previous());

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction(WidgetIconCommunity::widgetLabel());
    }

    /**
     * Base operations in order to render different list views
     *
     * @return string
     */
    protected function baseListsAction($pageTitle, $layout = null)
    {
        $enabledHierarchy = false;
        Url::remember();

        Yii::$app->view->params['textHelp']['filename'] = 'community_dashboard_description';
        $dataProvider = $this->getDataProvider();

        $urlCreation = ['/community/community/create'];

        if (!empty(Yii::$app->getModule('community')->enableWizard) && Yii::$app->getModule('community')->enableWizard == true) {
            $urlCreation = ['/community/community-wizard/introduction'];
        }
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosCommunity::tHtml('amoscommunity', 'Add new Community'),
            'urlCreateNew' => $urlCreation
        ];


        // if the visualization is a table show the community tree, else show all community fathers without subcommunities
        if (!empty($this->getCurrentView()['name']) && $this->getCurrentView()['name'] == 'grid') {

            $modelsearch      = new \open20\amos\community\models\search\CommunitySearch();
            if(empty(\Yii::$app->request->get()['CommunitySearch']['name'])) {

                $communitiesTree = $modelsearch->searchCommunityTreeOrder($dataProvider->query);
                $enabledHierarchy = true;
                $dataProvider = new \yii\data\ArrayDataProvider([
                    'allModels' => $communitiesTree,
                    'key' => 'id'
                ]);
            }
        } else {
            $moduleCommunity     = \Yii::$app->getModule('community');
            $showSubscommunities = $moduleCommunity->showSubcommunities;
            if (!$showSubscommunities) {
                $dataProvider->query->andWhere(['IS', 'parent_id', null]);
            }
        }


        Yii::$app->session->set(AmosCommunity::beginCreateNewSessionKey(), Url::previous());
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->setListsBreadcrumbs($pageTitle);
        return $this->render('index',
                [
                'dataProvider' => $dataProvider,
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null,
                'enabledHierarchy' => $enabledHierarchy
        ]);
    }

    /**
     * Used to set page title and breadcrumbs.
     *
     * @param string $pageTitle Page title (ie. Created by, ...)
     */
    protected function setListsBreadcrumbs($pageTitle)
    {
        $translatedTitle = AmosCommunity::t('amoscommunity', $pageTitle);
        $moduleCwh       = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                $moduleCwh->resetCwhScopeInSession();
            }
        }
        Yii::$app->view->title                 = $translatedTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $translatedTitle],
        ];
    }

    /**
     * Gets the list of all communities to which the logged user is registered
     *
     * @return string
     */
    public function actionMyCommunities($id = null)
    {
        Url::remember();
        Yii::$app->view->params['textHelp']['filename'] = 'community_dashboard_description';
        Yii::$app->session->set('previousUrl', Url::previous());
        if (!is_null($id)) {
            $url = $this->setCommunityById($id);
            if (!is_null($url)) {
                return ($url);
            }
        }

        $this->setDataProvider($this->getModelSearch()->searchMyCommunities(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction(WidgetIconMyCommunities::widgetLabel());
    }

    /**
     * Gets the list of all communities
     *
     * @return string
     */
    public function actionOwnInterestCommunities($id = null)
    {
        Url::remember();
        Yii::$app->view->params['textHelp']['filename'] = 'community_dashboard_description';
        Yii::$app->session->set('previousUrl', Url::previous());
        if (!is_null($id)) {
            $url = $this->setCommunityById($id);
            if (!is_null($url)) {
                return ($url);
            }
        }

        $this->setDataProvider($this->getModelSearch()->searchMyCommunitiesWithTags(Yii::$app->request->getQueryParams()));
        return $this->baseListsAction(WidgetIconMyCommunitiesWithTags::widgetLabel());
    }

    /**
     *
     * @param $id
     * @return
     */
    public function setCommunityById($id)
    {
        $model = $this->findModel($id);

        $userCommunity = CommunityUserMm::findOne(['user_id' => Yii::$app->user->id, 'community_id' => $id]);

        /**
         * If The User is not subscribed to community
         */
        if (empty($userCommunity)) {
            $this->addFlash('danger',
                AmosCommunity::t('amosadmin', 'You Can\'t access a community you are not a member of'));
            return $this->redirect(Url::previous());
        }

        if ($model != null) {
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh)) {
                $moduleCwh->setCwhScopeInSession([
                    'community' => $id,
                    ],
                    [
                    'mm_name' => 'community_user_mm',
                    'entity_id_field' => 'community_id',
                    'entity_id' => $id
                ]);
            }
        }

        return null;
    }

    /**
     *
     *
     * /**
     * Gets the list of all communities created by the logged user
     *
     * @return string
     */
    public function actionCreatedByCommunities()
    {
        Url::remember();
        Yii::$app->session->set('previousUrl', Url::previous());

        $this->setDataProvider($this->getModelSearch()->searchCreatedByCommunities(Yii::$app->request->getQueryParams()));
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p', AmosCommunity::t('amoscommunity', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setUpLayout('list');
        return $this->baseListsAction(WidgetIconCreatedByCommunities::widgetLabel());
    }

    /**
     * @return string
     */
    public function actionAdminAllCommunities()
    {
        Url::remember();
        Yii::$app->session->set('previousUrl', Url::previous());

        $this->setDataProvider($this->getModelSearch()->searchAdminAllCommunities(Yii::$app->request->getQueryParams()));
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p', AmosCommunity::t('amoscommunity', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        $this->setUpLayout('list');
        return $this->baseListsAction(WidgetIconAdminAllCommunity::widgetLabel());
    }

    /**
     * Gets the list of all communities to validate
     *
     * @return string
     */
    public function actionToValidateCommunities()
    {
        Url::remember();
        Yii::$app->session->set('previousUrl', Url::previous());

        $this->setDataProvider($this->getModelSearch()->searchToValidateCommunities(Yii::$app->request->getQueryParams()));
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt').Html::tag('p', AmosCommunity::t('amoscommunity', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));


        return $this->baseListsAction(WidgetIconToValidateCommunities::widgetLabel());
    }

    /**
     * Register an user in a community.
     *
     * Checks if an user already joined a community: if true, a message informing the user is already registered in that community is sent, else registers the user in the community
     *
     * @param integer $communityId
     * @param boolean $accept
     * @return string
     */
    public function actionJoinCommunity($communityId, $accept = false, $redirectAction = null)
    {
        $defaultAction = 'index';

        if (empty($redirectAction)) {
            $urlPrevious    = Url::previous();
            $redirectAction = $urlPrevious;
        }
        if (!$communityId) {
            $this->addFlash('danger',
                AmosCommunity::tHtml('amoscommunity',
                    "It is not possible to subscribe the user. Missing parameter community."));
            return $this->redirect($defaultAction);
        }

        $ok            = false;
        $nomeCognome   = " ";
        $communityName = ' ';
        $communityType = CommunityType::COMMUNITY_TYPE_OPEN;
        $userId        = Yii::$app->getUser()->getId();
        /** @var User $user */
        $user          = User::findOne($userId);
        /** @var UserProfile $userProfile */
        $userProfile   = $user->getProfile();
        if (!is_null($userProfile)) {
            $nomeCognome = " '".$userProfile->nomeCognome."' ";
        }

        $community = Community::findOne($communityId);
        if (!is_null($community)) {
            $communityName = " '".$community->name."'";
            $communityType = $community->community_type_id;
        }

        if (empty($redirectAction) && $community->context != Community::className()) {
            $defaultAction = Url::previous();
        }

        $userCommunity = CommunityUserMm::findOne(['community_id' => $communityId, 'user_id' => $userId]);

        // Verify if user already in community user relation table
        if (!is_null($userCommunity)) {
            if ($userCommunity->status == CommunityUserMm::STATUS_WAITING_OK_USER) { //user has been invited and decide to accept or reject
                $invitedByUser = User::findOne(['id' => $userCommunity->created_by]);
                if ($accept) {
//                    $communityManagerEmailArray = $userCommunity->getCommunityManagerMailList($communityId);
                    $userCommunity->status = CommunityUserMm::STATUS_ACTIVE;
                    $community->setCwhAuthAssignments($userCommunity);
                    $message               = AmosCommunity::tHtml('amoscommunity',
                            "You are now a member of the community").$communityName;
                    $ok                    = $userCommunity->save(false);
                    $emailTypeToManager    = EmailUtil::ACCEPT_INVITATION;
                    $emailTypeToUser       = EmailUtil::WELCOME;
                    $emailUtilToManager    = new EmailUtil($emailTypeToManager, $userCommunity->role, $community,
                        $userProfile->nomeCognome, '', null, $userProfile->user_id);
                    $emailUtilToUser       = new EmailUtil($emailTypeToUser, $userCommunity->role, $community,
                        $userProfile->nomeCognome, '', null, $userProfile->user_id);
                    $subjectToManager      = $emailUtilToManager->getSubject();
                    $textToManager         = $emailUtilToManager->getText();
                    $subjectToUser         = $emailUtilToUser->getSubject();
                    $textToUser            = $emailUtilToUser->getText();

                    $this->sendMail(null, $invitedByUser->email, $subjectToManager, $textToManager, [], []);
                    $this->sendMail(null, $user->email, $subjectToUser, $textToUser, [], []);
                } else {

                    $message   = AmosCommunity::tHtml('amoscommunity', "Invitation to").$communityName.' '.AmosCommunity::tHtml('amoscommunity',
                            "rejected successfully");
                    $emailType = EmailUtil:: REJECT_INVITATION;
                    $emailUtil = new EmailUtil($emailType, $userCommunity->role, $community, $userProfile->nomeCognome,
                        '', null, $userProfile->user_id);
                    $subject   = $emailUtil->getSubject();
                    $text      = $emailUtil->getText();
                    $userCommunity->delete();
                    $ok        = !$userCommunity->getErrors();
                    $this->sendMail(null, $invitedByUser->email, $subject, $text, [], []);
                }
            } else {
                $this->addFlash('info',
                    AmosCommunity::tHtml('amoscommunity', "User").$nomeCognome.AmosCommunity::tHtml('amoscommunity',
                        "already joined this community").$communityName);
                return $this->redirect($defaultAction);
            }
        } else {

            /**
             * If The User is not validated once - not possible to subscribe
             */
            if ($userProfile->validato_almeno_una_volta == 0 && $community->for_all_user == 0) {
                $this->addFlash('danger',
                    AmosCommunity::t('amoscommunity',
                        'You Can\'t Join Communities, your profile has never been validated'));

                return $this->redirect(Url::previous());
            }

            // Iscrivo l'utente alla community
            $userCommunity               = new CommunityUserMm();
            $userCommunity->community_id = $communityId;
            $userCommunity->user_id      = $userId;
            $callingModel                = Yii::createObject($community->context);
            $userCommunity->role         = $callingModel->getBaseRole();
            //mamagement status of new member and email sending depend on community type
            if ($communityType == CommunityType::COMMUNITY_TYPE_OPEN) {
                $userCommunity->status      = CommunityUserMm::STATUS_ACTIVE;
                //add cwh auth-assignment permission for community/user if role is participant and status is active
                $communityManagerEmailArray = $userCommunity->getCommunityManagerMailList($communityId);
                $community->setCwhAuthAssignments($userCommunity);
                $message                    = AmosCommunity::tHtml('amoscommunity', "You are now")." ".
                    AmosCommunity::tHtml('amoscommunity', $userCommunity->role)." ".
                    AmosCommunity::tHtml('amoscommunity', "of").$communityName;
                $emailTypeToManager         = EmailUtil::REGISTRATION_NOTIFICATION;
                $emailTypeToUser            = EmailUtil::WELCOME;
                $emailUtilToManager         = new EmailUtil($emailTypeToManager, $userCommunity->role, $community,
                    $userProfile->nomeCognome, '', null, $userProfile->user_id);
                $emailUtilToUser            = new EmailUtil($emailTypeToUser, $userCommunity->role, $community,
                    $userProfile->nomeCognome, '', null, $userProfile->user_id);
                $subjectToManager           = $emailUtilToManager->getSubject();
                $textToManager              = $emailUtilToManager->getText();
                $subjectToUser              = $emailUtilToUser->getSubject();
                $textToUser                 = $emailUtilToUser->getText();

                foreach ($communityManagerEmailArray as $to) {
                    $this->sendMail(null, $to, $subjectToManager, $textToManager, [], []);
                }
                $this->sendMail(null, $user->email, $subjectToUser, $textToUser, [], []);
            } elseif ($communityType == CommunityType::COMMUNITY_TYPE_CLOSED) {
                $this->addFlash('danger', AmosCommunity::tHtml('amoscommunity', "Can't Join Restricted Communities"));
                return $this->redirect($defaultAction);
            } else { //community is private type (not closed, if community is closed it will be not visible - only invite)
                $userCommunity->status      = CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER;
                $communityManagerEmailArray = $userCommunity->getCommunityManagerMailList($communityId);
                $message                    = AmosCommunity::tHtml('amoscommunity',
                        "Your request has been forwarded to managers of").
                    $communityName." ".AmosCommunity::tHtml('amoscommunity', "for approval");
                $emailType                  = EmailUtil::REGISTRATION_REQUEST;
                $emailUtil                  = new EmailUtil($emailType, $userCommunity->role, $community,
                    $userProfile->nomeCognome, '', null, $userProfile->user_id);
                $subject                    = $emailUtil->getSubject();
                $text                       = $emailUtil->getText();
                foreach ($communityManagerEmailArray as $to) {
                    $this->sendMail(null, $to, $subject, $text, [], []);
                }
            }

            $ok = $userCommunity->save(false);
        }

        if ($ok) {
            $this->addFlash('success', $message);
            if (isset($redirectAction)) {
                return $this->redirect($redirectAction);
            } else {
                return $this->redirect($defaultAction);
            }
        } else {
            $this->addFlash('danger',
                AmosCommunity::tHtml('amoscommunity', "Error occured while subscribing the user").$nomeCognome.AmosCommunity::tHtml('amoscommunity',
                    "to community").$communityName);
            return $this->redirect($defaultAction);
        }
    }

    /**
     * Community manager accepts the user registration request to a community
     *
     * @param $communityId
     * @param $userId
     * @return \yii\web\Response
     */
    public function actionAcceptUser($communityId, $userId)
    {
        return $this->redirect($this->acceptOrRejectUser($communityId, $userId, true));
    }

    /**
     * Community manager rejects the user registration request to a community
     *
     * @param int $communityId
     * @param int $userId
     * @return \yii\web\Response
     */
    public function actionRejectUser($communityId, $userId)
    {
        return $this->redirect($this->acceptOrRejectUser($communityId, $userId, false));
    }

    /**
     * @param int $communityId
     * @param int $userId
     * @param bool $acccept - true if User registration request has been accepted by community manager, false if rejected
     * @return string $redirectUrl
     */
    private function acceptOrRejectUser($communityId, $userId, $acccept)
    {
        $userCommunity = CommunityUserMm::findOne(['community_id' => $communityId, 'user_id' => $userId]);

        $status      = $acccept ? CommunityUserMm::STATUS_ACTIVE : CommunityUserMm::STATUS_REJECTED;
        $emailType   = $acccept ? EmailUtil::WELCOME : EmailUtil::REGISTRATION_REJECTED;
        $redirectUrl = "";
        $managerName = "";
        if (!is_null($userCommunity)) {
            $userCommunity->status = $status;
            $nomeCognome           = " ";
            $communityName         = '';

            /** @var UserProfile $userProfile */
            $user        = User::findOne($userId);
            $userProfile = $user->getProfile();
            if (!is_null($userProfile)) {
                $nomeCognome = " '".$userProfile->nomeCognome."' ";
            }
            $community = Community::findOne($communityId);
            if (!is_null($community)) {
                $communityName = " '".$community->name."'";
            }
            $callingModel = Yii::createObject($community->context);
            $redirectUrl  = Url::previous();

            if ($acccept) {
                $userCommunity->save(false);
                $community->setCwhAuthAssignments($userCommunity);
                $message = $nomeCognome.AmosCommunity::tHtml('amoscommunity', "is now").
                    " ".AmosCommunity::tHtml('amoscommunity', $userCommunity->role)." ".
                    AmosCommunity::tHtml('amoscommunity', "of").$communityName;
            } else {
                $loggedUser        = User::findOne(Yii::$app->getUser()->id);
                /** @var UserProfile $loggedUserProfile */
                $loggedUserProfile = $loggedUser->getProfile();
                $managerName       = $loggedUserProfile->getNomeCognome();
                $message           = AmosCommunity::tHtml('amoscommunity', "Registration request to").
                    $communityName." ".AmosCommunity::tHtml('amoscommunity', "sent by").
                    $nomeCognome.AmosCommunity::tHtml('amoscommunity', "has been rejected successfully");
            }
            $emailUtil = new EmailUtil($emailType, $userCommunity->role, $community, $userProfile->nomeCognome,
                $managerName, null, $userProfile->user_id);
            $subject   = $emailUtil->getSubject();
            $text      = $emailUtil->getText();
            $this->sendMail(null, $user->email, $subject, $text, [], []);
            $this->addFlash('success', $message);

            if (!$acccept) {
                $userCommunity->delete();
            }
        }
        return $redirectUrl;
    }

    /**
     * Action used to confirm a community manager.
     * @param int $communityId
     * @param int $userId
     * @param string $managerRole
     * @return \yii\web\Response
     */
    public function actionConfirmManager($communityId, $userId, $managerRole)
    {
        $ok = CommunityUtil::confirmCommunityManager($communityId, $userId, $managerRole);
        if ($ok) {
            $userProfile = UserProfile::findOne(['user_id' => $userId]);
            $this->addFlash('success',
                AmosCommunity::t('amoscommunity', "The manager '".$userProfile->getNomeCognome()."' is now active"));
        } else {
            $userProfile = UserProfile::findOne(['user_id' => $userId]);
            $this->addFlash('danger',
                AmosCommunity::t('amoscommunity',
                    "Error while confirming the manager '".$userProfile->getNomeCognome()."'"));
        }
        $redirectUrl = '';
        $community   = Community::findOne($communityId);
        if (!is_null($community)) {
            $callingModel = Yii::createObject($community->context);
            $redirectUrl  = $this->getRedirectUrl($callingModel, $communityId);
        }
        return $this->redirect($redirectUrl);
    }

    /**
     * @param $communityId
     * @param $userId
     */
    public function actionChangeUserRole($communityId, $userId)
    {

        $userCommunity = CommunityUserMm::findOne(['community_id' => $communityId, 'user_id' => $userId]);
        $this->model   = $this->findModel($communityId);

        if ($this->model->isCommunityManager(Yii::$app->user->id) || Yii::$app->user->can('ADMIN')) {

            if (Yii::$app->getRequest()->isAjax) {
                if (Yii::$app->request->isPost) {
                    $post = Yii::$app->request->post();
                    if (!is_null($userCommunity) && isset($post['role'])) {
                        $nomeCognome         = "";
                        $communityName       = '';
                        $userCommunity->role = $post['role'];
                        $ok                  = $userCommunity->save(false);
                        if ($ok) {
                            $this->model->setCwhAuthAssignments($userCommunity);
                            /** @var UserProfile $userProfile */
                            $user        = User::findOne($userId);
                            $userProfile = $user->getProfile();
                            if (!is_null($userProfile)) {
                                $nomeCognome = "'" . $userProfile->nomeCognome . "'";
                            }
                            if (!is_null($this->model)) {
                                $communityName = " '" . $this->model->name . "'";
                            }
                            $message = $nomeCognome . " " . AmosCommunity::t('amoscommunity', "is now") .
                                " '" . AmosCommunity::t('amoscommunity', $userCommunity->role) . "' " .
                                AmosCommunity::t('amoscommunity', "of") . $communityName;
                            $emailUtil = new EmailUtil(EmailUtil::CHANGE_ROLE, $userCommunity->role, $this->model,
                                $userProfile->nomeCognome, '', null, $userProfile->user_id);
                            $subject   = $emailUtil->getSubject();
                            $text      = $emailUtil->getText();
                            $this->sendMail(null, $user->email, $subject, $text, [], []);
                            $this->addFlash('success', $message);
                        }
                    }
                }
            }
        } else {
            $this->addFlash('danger', BaseAmosModule::t('amoscore', '#unauthorized_flash_message'));
        }
    }

    /**
     * Publish a community
     *
     * @param $id
     * @param bool $redirectWizard true if publishing at the end of creation creation wizard, false otherwise
     * @return string
     */
    public function actionPublish($id, $redirectWizard = true)
    {

        $this->model = $this->findModel($id);

        $published = false;
        $message   = AmosCommunity::t('amoscommunity', "#community_published")." '".$this->model->name."' ".AmosCommunity::t('amoscommunity',
                "has been published succesfully");

        //if community is already in validated status (maybe bypass workflow is active)
        if ($this->model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED) {
            $published = true;
        } else {
            $status = null;
            $user   = Yii::$app->getUser();

            $canValidateSubdomain = false;
            $isChild              = false;
            if ($this->model->parent_id != null) {
                $isChild              = true;
                $canValidateSubdomain = $user->can('COMMUNITY_VALIDATE', ['model' => $this->model]);
            }
            //if community is child check for permission validate under parent community domain
            //if community is not child check if user has validator role for community
            if ($canValidateSubdomain || ($user->can('COMMUNITY_VALIDATOR') && !$isChild)) {
                $status                       = Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED;
                //set flag validated at least once to TRUE
                $this->model->validated_once  = 1;
                //reset visible on edit flag
                $this->model->visible_on_edit = null;
                $published                    = true;
            } else {
                $status  = Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE;
                $message = AmosCommunity::t('amoscommunity', "Publication request for community")." '".$this->model->name."' ".AmosCommunity::t('amoscommunity',
                        "has been sent succesfully");
            }
            $this->model->status = $status;

            if ($this->model->save()) {
                if (!$redirectWizard) {
                    $this->addFlash('success', $message);
                }
            } else {
                $published = false;
                $message   = AmosCommunity::t('amoscommunity', "Error occured while publishing community")." '".$this->model->name."' ";
                $this->addFlash('error', $message);
            }
        }

        if ($redirectWizard) {
            return $this->render('closing',
                    [
                    'model' => $this->model,
                    'published' => $published,
                    'message' => $message
            ]);
        } else {
            return $this->redirect(Url::previous());
        }
    }

    /**
     * Reject publication of a community - status returns to draft
     *
     * @param $id
     * @return string
     */
    public function actionReject($id)
    {

        Url::remember();

        $this->model = $this->findModel($id);
        $status      = null;

        $message = AmosCommunity::t('amoscommunity', "Publication request for community")." '".$this->model->name."' ".AmosCommunity::t('amoscommunity',
                "has been rejected. Community status is back to 'Editing in progress'");
        if (Yii::$app->getUser()->can('COMMUNITY_VALIDATOR')) {
            $this->model->status          = Community::COMMUNITY_WORKFLOW_STATUS_DRAFT;
            $this->model->visible_on_edit = null;

            if ($this->model->save()) {
                $this->addFlash('success', $message);
            } else {
                $this->addFlash('error',
                    AmosCommunity::t('amoscommunity', "Error occured while rejecting publication request of community")." '".$this->model->name."' ");
            }
        }
        return $this->redirect('to-validate-communities');
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array $bcc
     */
    public function sendMail($from, $to, $subject, $text, $files, $bcc)
    {

        /** @var \open20\amos\emailmanager\AmosEmail $mailModule */
        $mailModule = Yii::$app->getModule("email");
        if (isset($mailModule)) {
            if (is_null($from)) {
                if (isset(Yii::$app->params['email-assistenza'])) {
                    //use default platform email assistance
                    $from = Yii::$app->params['email-assistenza'];
                } else {
                    $assistance = isset(Yii::$app->params['assistance']) ? Yii::$app->params['assistance'] : [];
                    $from       = isset($assistance['email']) ? $assistance['email'] : '';
                }
            }
            $tos = [$to];
            Email::sendMail($from, $tos, $subject, $text, $files, $bcc, [], 0, false);
        }
    }

    /**
     * Section Community Network (in edit or view mode) on user profile tab network
     * @param $userId
     * @param bool $isUpdate
     * @return string
     */
    public function actionUserNetwork($userId, $isUpdate = false)
    {

        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);

            return $this->render('user-network',
                    [
                    'userId' => $userId,
                    'isUpdate' => $isUpdate
            ]);
        }
        return null;
    }

    /**
     * Participants to a community/workgroup m2m widget - Ajax call to redraw the widget
     *
     * @param $id
     * @param $classname
     * @param array $params
     * @return string
     */
    public function actionCommunityMembers($id, $classname, array $params)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);

            $object                        = \Yii::createObject($classname);
            $model                         = $object->findOne($id);
            $showAdditionalAssociateButton = $params['showAdditionalAssociateButton'];
            $viewEmail                     = $params['viewEmail'];
            $checkManagerRole              = $params['checkManagerRole'];
            $addPermission                 = $params['addPermission'];
            $manageAttributesPermission    = $params['manageAttributesPermission'];
            $forceActionColumns            = $params['forceActionColumns'];
            $actionColumnsTemplate         = $params['actionColumnsTemplate'];
            $viewM2MWidgetGenericSearch    = $params['viewM2MWidgetGenericSearch'];
            $enableModal                   = $params['enableModal'];
            $gridId                        = $params['gridId'];
            $communityManagerRoleName      = $params['communityManagerRoleName'];
            $targetUrlInvitation      = $params['targetUrlInvitation'];
            $invitationModule      = $params['invitationModule'];

            return $this->render('community-members',
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
                    'communityManagerRoleName' => $communityManagerRoleName,
                    'targetUrlInvitation' => $targetUrlInvitation,
                    'invitationModule' => $invitationModule,
            ]);
        }
        return null;
    }

    /**
     * Participants to a community/workgroup m2m widget - Ajax call to redraw the widget
     *
     * @param $id
     * @param $classname
     * @param array $params
     * @return string
     */
    public function actionCommunityMembersMin($id, $classname, array $params)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);

            $object                        = \Yii::createObject($classname);
            $model                         = $object->findOne($id);
            $showAdditionalAssociateButton = $params['showAdditionalAssociateButton'];
            $viewEmail                     = $params['viewEmail'];
            $checkManagerRole              = $params['checkManagerRole'];
            $addPermission                 = $params['addPermission'];
            $manageAttributesPermission    = $params['manageAttributesPermission'];
            $forceActionColumns            = $params['forceActionColumns'];
            $actionColumnsTemplate         = $params['actionColumnsTemplate'];
            $viewM2MWidgetGenericSearch    = $params['viewM2MWidgetGenericSearch'];
            $enableModal                   = $params['enableModal'];
            $isUpdate                      = $params['isUpdate'];

            return $this->render('community-members-min',
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
                    'isUpdate' => $isUpdate
            ]);
        }
        return null;
    }

    /**
     * Associates user to a community if the user exists,
     * If the user does not exists, creates it, sends credential mail and associate it to the community.
     * @param int $id - community id
     * @return bool|string
     */
    public function actionInsassM2m($id)
    {
        $this->model = $this->findModel($id);
        $newUser     = false;

        $view         = 'insacc';
        $form         = new RegisterForm();
        $this->layout = false;
        if (Yii::$app->getRequest()->isAjax) {
            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                if ($form->load($post) && $form->validate()) {
                    $user = User::findOne(['email' => $form->email]);
                    if (!$user) {
                        $adminModule = Yii::$app->getModule('admin');
                        $result      = $adminModule->createNewAccount($form->nome, $form->cognome, $form->email, 0,
                            true, $this->model);
                        if (isset($result['user'])) {
                            $user    = $result['user'];
                            $newUser = true;
                        }
                    }

                    if (isset($user->id)) {
                        if (is_null(CommunityUserMm::findOne(['user_id' => $user->id, 'community_id' => $id]))) {
                            \Yii::$app->getModule('community')->createCommunityUser($id, CommunityUserMm::STATUS_ACTIVE,
                                $form->role, $user->id);
                        }

                        /** Send email of invitation to user if user exist already */
                        if (!$newUser) {
                            $loggedUser          = User::findOne(Yii::$app->getUser()->id);
                            /** @var UserProfile $loggedUserProfile */
                            $loggedUserProfile   = $loggedUser->getProfile();
                            /** @var User $userToInvite */
                            $userToInvite        = $user;
                            $userCommunity       = CommunityUserMm::findOne(['user_id' => $user->id, 'community_id' => $id]);
                            /** @var UserProfile $userToInviteProfile */
                            $userToInviteProfile = $userToInvite->getProfile();
                            $emailUtil           = new EmailUtil(EmailUtil::INVITATION, $userCommunity->role,
                                $userCommunity->community, $userToInviteProfile->nomeCognome,
                                $loggedUserProfile->getNomeCognome(), null, $userToInviteProfile->user_id);
                            $subject             = $emailUtil->getSubject();
                            $text                = $emailUtil->getText();
                            $this->sendMail(null, $userToInvite->email, $subject, $text, [], []);
                        }
                        return true;
                    }
                }
            }
        }
        return $this->renderAjax($view, ['model' => $form]);
    }

    /**
     * This action increment the hits on a community.
     * @param int $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIncrementCommunityHits($id)
    {
        $responseArray = ['success' => 1];
        /** @var Community $community */
        $community     = $this->findModel($id);
        $community->hits++;
        $ok            = $community->save(false);
        if (!$ok) {
            $responseArray['success'] = 0;
        }
        return json_encode($responseArray);
    }

    public function actionParticipants($communityId)
    {
        /** @var Community $model */
        Url::remember();
        $model = $this->findModel($communityId);
        
        return $this->render(
            'participants', 
            [
                'model' => $model,
                'targetUrlInvitation' => $this->targetUrlInvitation,
                'invitationModule' => $this->invitationModule
            ]
        );
    }

    /**
     * This action is called when a community is deleted.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeletedCommunity($id)
    {
        $community = Community::basicFind()->andWhere(['id' => $id])->one();
        if (!is_null($community)) {
            if (strlen($community->deleted_at) > 0) {
                return $this->render('deleted_community');
            } else {
                return $this->redirect(['view', 'id' => $id]);
            }
        }
        throw new NotFoundHttpException(BaseAmosModule::t('amoscore', 'The requested page does not exist.'));
    }

    public function actionUserJoinedReportDownload($communityId)
    {
        return $this->userJoinedReportDownload($communityId);
    }

    private function userJoinedReportDownload($communityId)
    {
        $community = Community::findOne(['id' => $communityId]);

        if (!empty($community)) {
            $usersJoinedQuery = CommunityUserMm::findAll(['community_id' => $communityId]);

            if (!empty($usersJoinedQuery)) {

                // Creating csv to convert in a excel file
                // This defines the header of the excel
                $usersJoinedCsv = AmosCommunity::t('amoscommunity', 'Community').' "'.$community->name."\"\n";
                // e.g. "Partecipanti Community LaMiaCommunity, Ruolo\n"
                $usersJoinedCsv .= AmosCommunity::t('amoscommunity', 'Partecipante').' '.
                    ','.AmosCommunity::t('amoscommunity', 'Ruolo')."\n";

                // This defines the list of users that joined community
                // e.g. "Amministratore Sistema, Community Manager\n"
                foreach ($usersJoinedQuery as $communityUserMm) {
                    $usersJoinedCsv .= UserProfile::findOne(['user_id' => $communityUserMm->user_id])->getNomeCognome().
                        ",".AmosCommunity::t('amoscommunity', $communityUserMm->role)."\n";
                }

                // Saving the csv created above in a temp directory, then yii2tech/spreadsheet
                // will convert the csv file in a excel file
                $file      = tempnam(sys_get_temp_dir(), 'excel_');
                $handle    = fopen($file, "w");
                fwrite($handle, $usersJoinedCsv);
                $csvReader = SpreadSheetFactory::createReader('Csv');
                $return    = $csvReader->load($file);
                fclose($handle);
                unlink($file);

                // Getting current sheet converted above by SpreadSheetFactory
                $sheet                         = $return->getActiveSheet();
                // Defining a style to apply when necessary
                $styleArray                    = array(
                    'font' => array(
                        'bold' => true
                    )
                );
                $styleArrayBgDarkGreyWhiteText = array(
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF'
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '4C4C4C',
                        ]
                    ]
                );
                $styleArrayBgGrey              = [
                    'font' => [
                        'bold' => true
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'DDDDDD',
                        ]
                    ]
                ];

                // Applying styles to sheet
                $sheet->getStyle('A1')->applyFromArray($styleArrayBgDarkGreyWhiteText);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A2')->applyFromArray($styleArrayBgGrey);
                $sheet->getStyle('B2')->applyFromArray($styleArrayBgGrey);
                foreach (range("A", "Z") as $colonna) {
                    $sheet->getColumnDimension($colonna)->setAutoSize(true);
                }
                $sheet->getStyleByColumnAndRow(0)->applyFromArray($styleArray);
                $sheet->mergeCells('A1:B1');
                $sheet->setAutoFilter('A2:B2');

                // Export formatted excel file
                $objWriter = SpreadSheetFactory::createWriter($return, 'Xls');
                $fileTmp   = tempnam(\Yii::getAlias('@webroot'), 'tmp');
                $objWriter->save($fileTmp);
                return \Yii::$app->response->sendFile($fileTmp, "PartecipantiCommunity{$communityId}.xls");
            }
        }
        return "";
    }
}