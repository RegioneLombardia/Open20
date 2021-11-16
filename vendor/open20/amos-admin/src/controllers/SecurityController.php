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
use open20\amos\admin\models\ForgotPasswordForm;
use open20\amos\admin\models\LoginForm;
use open20\amos\admin\models\ProfileReactivationForm;
use open20\amos\admin\models\RegisterForm;
use open20\amos\admin\models\TokenUsers;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileReactivationRequest;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\forms\FirstAccessForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\InvitationExternalInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\user\User;
use open20\amos\socialauth\models\SocialIdmUser;
use Hybridauth\User\Profile;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class SecurityController
 * @package open20\amos\admin\controllers
 */
class SecurityController extends BackendController
{
    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @var AmosAdmin $adminModule
     */
    protected $adminModule;

    /**
     * @inheritdoc
     */
    public function init()
    {
        /** @var AmosAdmin $adminModule */
        $this->adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'set-dl-semplification-modal-cookie',
                            'login',
                            'register',
                            'security-message',
                            'error',
                            'errore',
                            'reactivate-profile',
                            'forgot-password',
                            'insert-auth-data'
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'deimpersonate',
                            'check-session-scope',
                            'reset-dashboard-by-scope',
                            'reconciliation'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'impersonate'
                        ],
                        'allow' => true,
                        'roles' => ['IMPERSONATE_USERS'],
                    ],
                    [
                        'actions' => [
                            'unsubscribe',
                            'disable-notifications'
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $this->setUpLayout('main');
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'shibboleth' => [
                'class' => 'asasmoyo\yii2saml\actions\LoginAction'
            ],
            'acs' => [
                'class' => 'asasmoyo\yii2saml\actions\AcsAction',
                'successCallback' => [$this, 'shibbolethAuthentication'],
                'successUrl' => Url::to('/'),
            ]
        ];
    }

    /**
     * Login action and function called on login view.
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
        $this->setUpLayout('login');

        /** @var LoginForm $model */
        $model = $this->adminModule->createModel('LoginForm');
        $token = \Yii::$app->request->get('token');


        if (!Yii::$app->user->isGuest && empty($token)) {
            return $this->goHome();
        }

        //login by token and redirect
        if (!empty($token)) {
            $this->loginByToken($model, $token);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->adminModule->allowLoginWithEmailOrUsername) {
                $user = User::findByUsernameOrEmail($model->usernameOrEmail);
            } else {
                $user = User::findByUsername($model->username);
            }

            if (is_null($user)) {
                if ($this->adminModule->allowLoginWithEmailOrUsername) {
                    $inactiveUser = User::findByUsernameOrEmailInactive($model->usernameOrEmail);
                } else {
                    $inactiveUser = User::findByUsernameInactive($model->username);
                }

                if (!is_null($inactiveUser)) {
                    return $this->redirect('/' . AmosAdmin::getModuleName() . '/security/reactivate-profile?userdisabled');
                }

                // Trigger validation for password check
                $model->validate();

                $viewToRender = 'login' . ($this->adminModule->enableDlSemplification ? '_dl_semplification' : '');
                return $this->render($viewToRender, [
                    'model' => $model,
                ]);
            }

            if ($model->login()) {
                /** @var \open20\amos\socialauth\Module $socialModule */
                $socialModule = Yii::$app->getModule('socialauth');
    
                if ($this->adminModule->enableDlSemplification && !is_null($socialModule)) {
                    /** @var SocialIdmUser $socialIdmUser */
                    $socialIdmUser = $socialModule->findSocialIdmByUserId($user->id);
                    if (!is_null($socialIdmUser)) {
                        Yii::$app->user->logout();
                        Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#dl_semplification_cannot_login_with_basic_auth'));
                        return $this->goHome();
                    }
                }
    
                /* per amos */
                if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']) {
                    $ruolo = \Yii::$app->authManager->getRole($model->ruolo);
                    $userId = \Yii::$app->getUser()->getId();
                    \Yii::$app->authManager->revokeAll($userId);
                    \Yii::$app->authManager->assign($ruolo, $userId);
                }

                //Autogenerated reset widgets
                if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos'] && !is_null(Yii::$app->getModule('build'))) {
                    $this->run('/build/default/crea-dashboard');
                }

                // if google contact service enabled reload in session some contact data by google account
                AmosAdmin::fetchGoogleContacts();

                //If the module is enabled then create social user
                if ($socialModule && $socialModule->id) {
                    //Provider is in session
                    $provider = Yii::$app->session->get('social-match');

                    //If is set social match i nett to link user
                    if ($provider) {
                        //pre-compile with social-auth session data
                        $socialProfile = \Yii::$app->session->get('social-profile');

                        //The user profile
                        $userProfile = $user->profile;

                        //Create link
                        $this->createSocialUser($userProfile, $socialProfile, $provider);
                    }
                }

                /** @var  $response  Response */
//                $response = $this->goBack();
////                $current = Url::();
//                $anchor = preg_match('/#(.)+/', $_SERVER['HTTP_REFERER']);
//
//                pr($anchor);die;
//                $url = $response->headers['location'].$anchor;
//                return $this->redirect($url);
                return $this->goBack();
            }
        } else {

            //pre-compile with social-auth session data
            $socialProfile = \Yii::$app->session->get('social-profile');

            if ($socialProfile) {
                $model->username = $socialProfile->email;
            }
        }
        
        $viewToRender = 'login' . ($this->adminModule->enableDlSemplification ? '_dl_semplification' : '');
        return $this->render($viewToRender, [
            'model' => $model,
        ]);
    }

    /**
     * Login function called in case of automatic login needs.
     * @param string $usernameOrEmail
     * @param string $password
     * @param int|null $community_id
     * @param string|null $postLoginUrl
     * @return string|Response
     * @throws \Exception
     */
    public function login($usernameOrEmail, $password, $community_id = null, $postLoginUrl = null, $isFirstAccess = false)
    {
        /** @var LoginForm $model */
        $model = $this->adminModule->createModel('LoginForm');
        $model->password = $password;
        if ($this->adminModule->allowLoginWithEmailOrUsername) {
            $model->usernameOrEmail = $usernameOrEmail;
            $user = User::findByUsernameOrEmail($model->usernameOrEmail);
        } else {
            $model->username = $usernameOrEmail;
            $user = User::findByUsername($model->username);
        }

        if (is_null($user)) {
            if ($this->adminModule->allowLoginWithEmailOrUsername) {
                $inactiveUser = User::findByUsernameOrEmailInactive($model->usernameOrEmail);
            } else {
                $inactiveUser = User::findByUsernameInactive($model->username);
            }

            if (!is_null($inactiveUser)) {
                return $this->redirect('/' . AmosAdmin::getModuleName() . '/security/reactivate-profile?userdisabled');
            }

            //Trigger validation for password check
            $model->validate();

            return $this->render('login', [
                'model' => $model,
            ]);
        }

        if ($model->login()) {
            /* per amos */
            if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']) {
                $ruolo = \Yii::$app->authManager->getRole($model->ruolo);
                $userId = \Yii::$app->getUser()->getId();
                \Yii::$app->authManager->revokeAll($userId);
                \Yii::$app->authManager->assign($ruolo, $userId);
            }

            //Autogenerated reset widgets
            if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos'] && !is_null(Yii::$app->getModule('build'))) {
                $this->run('/build/default/crea-dashboard');
            }

            if ($isFirstAccess && !is_null($user->userProfile->first_access_mail_url)) {
                $mailUrl = $user->userProfile->first_access_mail_url;
                $userProfile = $user->userProfile;
                $userProfile->first_access_mail_url = null;
                $userProfile->save(false);
                if (!is_null($postLoginUrl)) {
                    return $this->redirect($postLoginUrl);
                }
                return $this->redirect($mailUrl . '?user_id=' . $user->id);
            } else if (!is_null($postLoginUrl)) {
                return $this->redirect($postLoginUrl);
            } else if ($community_id != null) {
                return $this->redirect(Yii::$app->getUrlManager()->createUrl(['/community/join', 'id' => $community_id, 'subscribe' => 1]));
            } else {
                return $this->goBack();
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     * @return string
     */
    public function actionLogout($goToFrontPage = false, $backTo = null)
    {
        Yii::$app->user->logout();
        if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']) {
            $idUtente = Yii::$app->getUser()->getId();
            $ids = \open20\amos\dashboard\models\AmosUserDashboards::find()->andWhere(['user_id' => $idUtente])->select('id');
            \open20\amos\dashboard\models\AmosUserDashboardsWidgetMm::deleteAll(['IN', 'amos_user_dashboards_id',
                $ids]);
            \open20\amos\dashboard\models\AmosUserDashboards::deleteAll(['user_id' => $idUtente]);
        }
        if ($goToFrontPage) {
            if (array_key_exists("platform", Yii::$app->params)) {
                if (array_key_exists("frontendUrl", Yii::$app->params['platform'])) {
                    return $this->redirect(Yii::$app->params['platform']['frontendUrl']);
                }
            }
            if (!$backTo) {
                return $this->goHome();
            } else {
                return $this->redirect($backTo);
            }
        } else {
            if (!$backTo) {
                return $this->goHome();
            } else {
                return $this->redirect($backTo);
            }
        }
    }

    /**
     * Action to request the reactivation of a profile.
     * @return string
     */
    public function actionReactivateProfile()
    {
        $this->setUpLayout('login');

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        /** @var ProfileReactivationForm $model */
        $model = $this->adminModule->createModel('ProfileReactivationForm');

        /**
         * If $userId is false the user is not created
         */
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::find()->andWhere(['email' => $model->email])->one();
            if (!empty($user)) {
                if ($user->userProfile->attivo == UserProfile::STATUS_ACTIVE) {
                    $userProfile = new UserProfileController($user->id, $this->module);
                    $userProfile->sendCredentialsMail($user->userProfile);
                    Yii::$app->session->addFlash('success',
                        AmosAdmin::t('amosadmin',
                            "Se l'utente risulterà attivo, verrà inviata una email all'indirizzo indicato per reimpostare la password di accesso al sistema, altrimenti verrà inviata una richiesta di riattivazione del profilo."));
                } else {
                    $reactRequest = UserProfileReactivationRequest::findOne(['user_profile_id' => $user->userProfile->id]);
                    if (empty($reactRequest)) {
                        $reactRequest = new UserProfileReactivationRequest();
                        $reactRequest->user_profile_id = $user->userProfile->id;
                        $reactRequest->message = $model->message;
                    } else {
                        $reactRequest->message .= "<br>" . $model->message;
                    }
                    $reactRequest->save();
                    $ok = $model->sendMail();
                    if ($ok) {
                        Yii::$app->session->addFlash('success',
                            AmosAdmin::t('amosadmin',
                                'Se l\'utente risulterà attivo, verrà inviata una email all\'indirizzo indicato per reimpostare la password di accesso al sistema, altrimenti verrà inviata una richiesta di riattivazione del profilo.'));

                        $model = new ProfileReactivationForm(); // To empty all fields
                    } else {
                        Yii::$app->session->addFlash('danger',
                            AmosAdmin::t('amosadmin', 'Error while sending reactivation request.'));
                    }
                }
            }
        }

        return $this->render('reactivate-profile', [
            'model' => $model,
        ]);
    }

    /**
     * @param RegisterForm $model
     */
    protected function beforeRegisterNewUser($model)
    {

    }

    /**
     * @param RegisterForm $model
     * @param UserProfile $userProfile
     */
    protected function afterRegisterNewUser($model, $userProfile)
    {
        UserProfileUtility::updateTagTreesAfterUserCreation($userProfile);
    }

    /**
     * @return bool|\yii\web\Response
     */
    public function actionRegister()
    {
        $this->setUpLayout('login');

        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->set('removeAfterLogout', 'true');
            Yii::$app->user->logout();
            Yii::$app->session->remove('removeAfterLogout');
        }

        /**
         * If signup is not enabled
         * */
        if (!$this->module->enableRegister) {
            if (!empty($this->module->textWarningForRegisterDisabled)) {
                Yii::$app->session->addFlash('warning',
                    AmosAdmin::t('amosadmin', $this->module->textWarningForRegisterDisabled));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Signup Disabled'));
            }

            return $this->goHome();
        }

        /**
         * If the mail is not set i can't create user
         *
         * if(empty($userProfile->email)) {
         * Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Unable to register, missing mail permission'));
         *
         * return $this->goHome();
         * } */
        /** @var RegisterForm $model */
        $model = $this->adminModule->createModel('RegisterForm');

        //pre-compile form datas from get params
        $getParams = \Yii::$app->request->get();

        //pre-compile with social-auth session data
        $socialProfile = \Yii::$app->session->get('social-profile');

        // Pre-compile with SPID session data
        $spidData = \Yii::$app->session->get('IDM');

        if (!empty($getParams['name']) && !empty($getParams['surname']) && !empty($getParams['email'])) {
            $model->nome = $getParams['name'];
            $model->cognome = $getParams['surname'];
            $model->email = $getParams['email'];
        } elseif ($socialProfile && $socialProfile->email) {
            $model->nome = $socialProfile->firstName;
            $model->cognome = $socialProfile->lastName;
            $model->email = $socialProfile->email;
        } elseif ($spidData && $spidData['emailAddress']) {
            $model->nome = $spidData['nome'];
            $model->cognome = $spidData['cognome'];
            $model->email = $spidData['emailAddress'];
        }
    
        if ($this->adminModule->enableDlSemplification && !$spidData) {
            if (!empty($this->module->textWarningForRegisterDisabled)) {
                Yii::$app->session->addFlash('warning',
                    AmosAdmin::t('amosadmin', $this->module->textWarningForRegisterDisabled));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Signup Disabled'));
            }
        
            return $this->goHome();
        }

        // Invitation User id
        $iuid = isset($getParams['iuid']) ? $getParams['iuid'] : null;

        if ($model->load(Yii::$app->request->post())) {
            $this->beforeRegisterNewUser($model);
            /**
             * @var $newUser integer False or UserId
             */
            $newUser = $this->adminModule->createNewAccount(
                $model->nome, $model->cognome, $model->email, $model->privacy, false, null, \Yii::$app->request->post('redirectUrl')
            );

            /**
             * If $newUser is false the user is not created
             */
            if (!$newUser || isset($newUser['error'])) {
                //Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#error_unable_to_register'));
                $result_message = [];
                $errorMail = Html::encode($model->email ? $model->email : '');
                array_push($result_message,
                    AmosAdmin::t('amosadmin', '#error_register_user', ['errorMail' => $errorMail]));

                //  Commentato quando è stato cambiato il messaggio di errore. La richiesta era di far vedere solamente il messaggio
                // di errore e non gli errori successivi in quanto ritenuti duplicati.
//                if ($newUser['messages']) {
//                    foreach ($newUser['messages'] as $message) {
//                        //Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', reset($message)));
//                        array_push($result_message, AmosAdmin::t('amosadmin', reset($message)));
//                    }
//                }

                return $this->render('security-message',
                    [
                        'title_message' => AmosAdmin::t('amosadmin', 'Spiacenti'),
                        'result_message' => $result_message,
                        'go_to_login_url' => Url::current()
                    ]);

                //return $this->goHome();
            }

            $userId = $newUser['user']->id;

            /** @var UserProfile $userProfileModel */
            $userProfileModel = $this->adminModule->createModel('UserProfile');
            /**
             * @var $newUserProfile UserProfile
             */
            $newUserProfile = $userProfileModel::findOne(['user_id' => $userId]);

            /**
             * If $newUser is false the user is not created
             */
            if (!$newUserProfile || !$newUserProfile->id) {
                //Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Error when loading profile data, try again'));

                return $this->render('security-message',
                    [
                        'title_message' => AmosAdmin::t('amosadmin', 'Errore'),
                        'result_message' => AmosAdmin::t('amosadmin', 'Error when loading profile data, try again'),
                        'go_to_login_url' => Url::current()
                    ]);

                //return $this->goHome();
            }
            $this->afterRegisterNewUser($model, $newUserProfile);

            //Social Auth trigger
            $socialModule = Yii::$app->getModule('socialauth');

            //If the module is enabled then create social user
            if ($socialModule && $socialModule->id) {
                //Provider is in session
                $provider = Yii::$app->session->get('social-pending');

                //If is set social match i nett to link user
                if ($provider) {
                    $this->createSocialUser($newUserProfile, $socialProfile, $provider);

                }
            }

            $iuid = \Yii::$app->request->post('iuid');

            $communityId = \Yii::$app->request->post('community');
            $community = null;
            if (\Yii::$app->getModule('community')) {
                $community = \open20\amos\community\models\Community::findOne($communityId);
            }

            if (!empty($getParams['moduleName']) && !empty($getParams['contextModelId'])) {
                /** @var AmosModule $module */
                $module = Yii::$app->getModule($getParams['moduleName']);
                if (!is_null($module) && ($module instanceof InvitationExternalInterface)) {
                    $okUserContextAssociation = $module->addUserContextAssociation($userId, $getParams['contextModelId']);
                    if (!$okUserContextAssociation) {
                        Yii::$app->getSession()->addFlash('danger', AmosAdmin::t('amosadmin', '#user_context_association_error'));
                    }
                }
            }

            $sent = UserProfileUtility::sendCredentialsMail($newUserProfile, $community);

            if (!$sent) {
                //Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#error_send_register_mail'));
                return $this->render('security-message',
                    [
                        'title_message' => AmosAdmin::t('amosadmin', '#error'),
                        'result_message' => AmosAdmin::t('amosadmin', '#error_send_register_mail')
                    ]);
            } else {
                //Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', 'An email has been sent to') . ' ' . $model->email);

                // Sent notification email to invitation user
                if ($iuid != null) {
                    $sent = UserProfileUtility::sendUserAcceptRegistrationRequestMail($newUserProfile, $community, $iuid);
                }
                
                $msg1 = '#msg_complete_registration_result_1';
                $msg2 = '#msg_complete_registration_result_2';
                if ($this->adminModule->enableDlSemplification) {
                    $msg1 .= '_dl_semplification';
                    $msg2 .= '_dl_semplification';
                }
                
                return $this->render('security-message', [
                    'title_message' => AmosAdmin::t('amosadmin', '#msg_complete_registration_title'),
                    'result_message' => [
                        AmosAdmin::t('amosadmin', $msg1) . '<br>' . Html::tag('span', Html::encode($model->email)),
                        AmosAdmin::t('amosadmin', $msg2)
                    ]
                ]);
            }

            //return $this->goHome();

        }
    
        $viewToRender = 'register' . ($this->adminModule->enableDlSemplification ? '_dl_semplification' : '');
        return $this->render($viewToRender,
            [
                'model' => $model,
                'iuid' => $iuid,
                'codiceFiscale' => ($this->adminModule->enableDlSemplification && $spidData && $spidData['codiceFiscale'] ? $spidData['codiceFiscale'] : null)
            ]);
    }
    
    /**
     * @return string
     */
    public function actionSecurityMessage()
    {
        $this->setUpLayout('login');
        return $this->render('security-message', [
            'result_message' => 'prova messaggio'
        ]);
    }

    /**
     * Forgotten password form
     * @return string|\yii\web\Response
     */
    public function actionForgotPassword()
    {
        $this->setUpLayout('login');

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        /** @var ForgotPasswordForm $model */
        $model = $this->adminModule->createModel('ForgotPasswordForm');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->email != NULL) {
                $dati_utente = $model->verifyEmail($model->email);
                if ($dati_utente) {
                    /** @var \open20\amos\socialauth\Module $socialModule */
                    $socialModule = Yii::$app->getModule('socialauth');
                    if ($this->adminModule->enableDlSemplification) {
                        if (!is_null($socialModule)) {
                            /** @var SocialIdmUser $socialIdmUser */
                            $socialIdmUser = $socialModule->findSocialIdmByUserId($dati_utente->id);
                            if (!is_null($socialIdmUser)) {
                                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#dl_semplification_cannot_login_with_basic_auth'));
                                return $this->render('forgot_password', [
                                    'model' => $model
                                ]);
                            }
                        }
                        if (UserProfileUtility::isExpiredDateDlSemplification()) {
                            Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', '#dl_semplification_cannot_change_password'));
                            return $this->render('forgot_password', [
                                'model' => $model
                            ]);
                        }
                    }
                    if ($dati_utente->userProfile->isDeactivated()) {
                        return $this->redirect('/' . AmosAdmin::getModuleName() . '/security/reactivate-profile?userdisabled');
                    }
                    $urlCurrent = null;
                    $urlCurrentParam = Yii::$app->getRequest()->get('url_current');
                    if (!is_null(Yii::$app->getRequest()->get('url_current'))) {
                        $urlCurrent = $urlCurrentParam;
                    }
                    $this->actionSpedisciCredenziali($dati_utente->userProfile->id, true, true, $urlCurrent);
                }
                return $this->render('security-message',
                    [
                        'title_message' => AmosAdmin::t('amosadmin', '#msg_forgot_pwd_title'),
                        'result_message' => [
                            AmosAdmin::t('amosadmin', '#msg_forgot_pwd_result_1') . '<br>' . Html::tag('span', Html::encode($model->email)),
                            AmosAdmin::t('amosadmin', '#msg_forgot_pwd_result_2')
                        ],
                        'go_to_login_url' => !is_null(Yii::$app->getRequest()->get('return_url')) ? Yii::$app->getRequest()->get('return_url') : Url::current(),
                    ]);
            }
        }

        return $this->render('forgot_password', [
            'model' => $model,
        ]);
    }

    /**
     * Send Login-infos to user
     * @param int $id UserProfile ID
     * @param bool $isForgotPasswordView set true if this function is called from the forgot-password view to avoid appearing of flash messages
     * @param bool $isForgotPasswordRequest set true if this function is called from a reset password request action
     * @param string $urlCurrent The previous link to use in mail.
     * @return mixed
     */
    public function actionSpedisciCredenziali($id, $isForgotPasswordView = false, $isForgotPasswordRequest = false, $urlCurrent = null)
    {
        /** @var UserProfile $userProfileModel */
        $userProfileModel = $this->adminModule->createModel('UserProfile');
        $model = $userProfileModel::findOne($id);
        if ($model && $model->user && $model->user->email) {
            $model->user->generatePasswordResetToken();
            $model->user->save(false);
            if (!$isForgotPasswordRequest) {
                $sent = UserProfileUtility::sendCredentialsMail($model);
            } else {
                $sent = UserProfileUtility::sendPasswordResetMail($model, null, $urlCurrent);
            }
            if ($sent) {
                if (!$isForgotPasswordView) {
                    Yii::$app->session->addFlash('success',
                        AmosAdmin::t('amosadmin', 'Credenziali spedite correttamente alla email {email}',
                            ['email' => $model->user->email]));
                }
            } else {
                if (!$isForgotPasswordView) {
                    Yii::$app->session->addFlash('danger',
                        AmosAdmin::t('amosadmin', 'Si è verificato un errore durante la spedizione delle credenziali'));
                }
            }
        } else {
            if (!$isForgotPasswordView) {
                //Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'L\'utente non esiste o è sprovvisto di email, impossibile spedire le credenziali'));
                Yii::$app->session->addFlash('danger',
                    AmosAdmin::t('amosadmin', 'Si è verificato un errore durante la spedizione delle credenziali'));
            }
        }
        if (!$isForgotPasswordView) {
            return $this->redirect(Url::previous());
        }
    }

    /**
     * @param int $user_id
     * @return \yii\web\Response
     */
    public function actionImpersonate($user_id)
    {
        //Remember Impersonator
        $impersonator = Yii::$app->user->id;

        //Timeout login
        $loginTimeout = Yii::$app->params['loginTimeout'] ?: 3600;

        //Go out from this user
        Yii::$app->user->logout();

        //New user identity
        $identity = User::findOne(['id' => $user_id]);

        //Login to selected user
        Yii::$app->user->login($identity, $loginTimeout);

        //Set Current admin user in session
        Yii::$app->session->set('IMPERSONATOR', $impersonator);

        return $this->redirect(['/']);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDeimpersonate()
    {
        if (Yii::$app->session->has('IMPERSONATOR')) {
            //Get Impersonator
            $impersonator = Yii::$app->session->get('IMPERSONATOR');

            //Remove admin user in session
            Yii::$app->session->remove('IMPERSONATOR');

            //Timeout login
            $loginTimeout = 3600 * 24 * 30;
            if (!empty(\Yii::$app->user->authTimeout)) {
                $loginTimeout = \Yii::$app->user->authTimeout;
            }

            //Go out from this user
            Yii::$app->user->logout();

            //New user identity
            $identity = User::findOne(['id' => $impersonator]);

            //Login to selected user
            Yii::$app->user->login($identity, $loginTimeout);
        }

        return $this->redirect(['/']);
    }

    /**
     * Login-info choice at register step
     * @return string
     */
    public function actionInsertAuthData()
    {
        $this->setUpLayout('login');
        $password_reset_token = null;
        $user = null;
        $username = null;
        $community_id = null;
        $redirectUrl = \Yii::$app->getUser()->loginUrl;
        $precompileUsernameOnFirstAccess = $this->module->precompileUsernameOnFirstAccess;
        $isFirstAccess = false;
        if (NULL !== (Yii::$app->getRequest()->getQueryParam('token'))) {
            $password_reset_token = Yii::$app->getRequest()->getQueryParam('token');
            $user = User::findByPasswordResetToken($password_reset_token);
            if ($user) {
                $username = $user->username;
                $isFirstAccess = (empty($user->password_hash) && !$user->userProfile->privacy);
            }
        }


        $postLoginUrl = null;
        if (!is_null(Yii::$app->getRequest()->get('url_previous'))) {
            $postLoginUrl = Yii::$app->getRequest()->get('url_previous');
        }

        if ((Yii::$app->getRequest()->get('community_id')) !== NULL) {
            $community_id = Yii::$app->getRequest()->getQueryParam('community_id');
//            $postLoginUrl  = Yii::$app->getUrlManager()->createUrl(['/community/join', 'id' => $community_id]);
        }
        if ($user && !$username) {
            if (Yii::$app->request->isPost) {
                $model = new FirstAccessForm();
                if ($isFirstAccess && is_null($user->userProfile->privacy)) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                }
                if ($model->load(Yii::$app->request->post())) {
                    if ($model->verifyUsername($model->username)) {
                        Yii::$app->getSession()->addFlash('danger',
                            Yii::t('amosadmin',
                                'Attenzione! La username inserita &egrave; gi&agrave; in uso. Sceglierne un&#39;altra.'));
                        return $this->render('first_access',
                            [
                                'model' => $model,
                                'isFirstAccess' => $isFirstAccess && is_null($user->userProfile->privacy)
                            ]);
                    } else {
                        $user->setPassword($model->password);
                        $user->username = $model->username;
                        if ($user->validate() && $user->save()) {
                            Yii::$app->getSession()->addFlash('success',
                                Yii::t('amosadmin', 'Perfetto! Hai scelto correttamente le tue credenziali.'));
                            $user->removePasswordResetToken();
                            $user->save();
                            if ($isFirstAccess) {
                                $profile = $user->userProfile;
                                $profile->privacy = 1;
                                $profile->save(false);
                            }
                            return $this->login($model->username, $model->password, $community_id, $postLoginUrl, $isFirstAccess);
                        } else {
                            //return $this->render('login_error', ['message' => Yii::t('amosadmin', " Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.")]);
                            return $this->render('security-message',
                                [
                                    'title_message' => AmosAdmin::t('amosadmin', 'Spiacenti'),
                                    'result_message' => AmosAdmin::t('amosadmin',
                                        " Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.")
                                ]);
                        }
                    }
                } else {
                    $model->token = $password_reset_token;
                    return $this->render('first_access',
                        [
                            'model' => $model,
                            'isFirstAccess' => $isFirstAccess
                        ]);
                }
            } else {
                $model = new FirstAccessForm();
                if ($precompileUsernameOnFirstAccess) {
                    $model->username = $user->email;
                }
                if ($isFirstAccess) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                }
                $model->token = $password_reset_token;
                return $this->render('first_access',
                    [
                        'model' => $model,
                        'isFirstAccess' => $isFirstAccess && is_null($user->userProfile->privacy)
                    ]);
            }
        } else if ($user && $username) {

            if (Yii::$app->request->isPost) {
                $model = new FirstAccessForm();
                if ($isFirstAccess && is_null($user->userProfile->privacy)) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                }
                if ($model->load(Yii::$app->request->post())) {

                    $user->setPassword($model->password);

                    if ($user->validate() && $user->save()) {
                        Yii::$app->getSession()->addFlash('success',
                            Yii::t('amosadmin', 'Perfetto! Hai scelto correttamente la tua password.'));
                        $user->removePasswordResetToken();
                        $user->save();
                        if ($isFirstAccess) {
                            $profile = $user->userProfile;
                            $profile->privacy = 1;
                            $profile->save(false);
                        }
                        return $this->login($username, $model->password, $community_id, $postLoginUrl, $isFirstAccess);
                    } else {
                        //return $this->render('login_error', ['message' => Yii::t('amosadmin', " Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.")]);
                        return $this->render('security-message',
                            [
                                'title_message' => AmosAdmin::t('amosadmin', 'Spiacenti'),
                                'result_message' => AmosAdmin::t('amosadmin',
                                    " Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.")
                            ]);
                    }
                } else {
                    $model->token = $password_reset_token;
                    $model->username = $username;
                    return $this->render('reset_password',
                        [
                            'model' => $model,
                            'isFirstAccess' => $isFirstAccess && is_null($user->userProfile->privacy)
                        ]);
                }
            } else {
                $model = new FirstAccessForm();
                if ($isFirstAccess && is_null($user->userProfile->privacy)) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                }
                $model->token = $password_reset_token;
                $model->username = $username;
                return $this->render('reset_password',
                    [
                        'model' => $model,
                        'isFirstAccess' => $isFirstAccess && is_null($user->userProfile->privacy)
                    ]);
            }
        } else {
            //return $this->render('login_error', ['message' => Yii::t('amosadmin', ' Errore! Il tempo per poter accedere è scaduto. Contatti l\'amministratore e si faccia reinviare la mail di accesso.')]);
            $tokenErrorMessage = AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message");

            // Pickup assistance params
            $assistance = isset(\Yii::$app->params['assistance']) ? \Yii::$app->params['assistance'] : [];

            // Check if is in email mode
            $isMail = ((isset($assistance['type']) && $assistance['type'] == 'email') || (!isset($assistance['type'])
                    && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
            $mailAddress = isset($assistance['email']) ? $assistance['email'] : (isset(\Yii::$app->params['email-assistenza'])
                ? \Yii::$app->params['email-assistenza'] : '');
            $linkHref = $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : '');
            if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))) {
                $tokenErrorMessage .= Html::tag('br') .
                    AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_contact_assistance") . ' ' .
                    Html::a(
                        AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"), $linkHref,
                        ['title' => Yii::t('amoscore', 'Verrà aperta una nuova finestra'), 'style' => 'color:white']
                    ) . Html::tag('br') . AmosAdmin::t('amosadmin',
                        "#insert_auth_data_token_expired_message_forgot_password_else") . ' '
                    . (Html::a(
                        AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"),
                        ['/' . AmosAdmin::getModuleName() . '/security/forgot-password'],
                        ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link'), 'style' => 'color:white']
                    ));
            } else {
                $tokenErrorMessage .= Html::tag('br') .
                    AmosAdmin::t('amosadmin', "#forgot_password_title_link") . ' ' .
                    Html::a(
                        AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"),
                        ['/' . AmosAdmin::getModuleName() . '/security/forgot-password'],
                        ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link')]
                    );
            }
            return $this->render('security-message',
                [
                    'title_message' => AmosAdmin::t('amosadmin', 'Spiacenti'),
                    'result_message' => $tokenErrorMessage,
                    'hideGoBackBtn' => true
                ]);
        }
    }

    /**
     * Action to unsubscribe a user from the notification emails.
     * @param int $id
     * @param string $token
     * @return string
     */
    public function actionUnsubscribe($id, $token)
    {
        $user = User::findOne(['id' => $id]);
        $message = AmosAdmin::t('amosadmin', '#unsubscribe_message_invalid_user');
        if (!is_null($user)) {
            $md5Username = md5($user->username);
            if ($md5Username == $token) {
                $notifyModule = Yii::$app->getModule('notify');
                if (!is_null($notifyModule)) {
                    /** @var \open20\amos\notificationmanager\AmosNotify $notifyModule */
                    $ok = $notifyModule->saveNotificationConf($id,
                        \open20\amos\notificationmanager\models\NotificationsConfOpt::EMAIL_OFF);
                    if ($ok) {
                        $message = AmosAdmin::t('amosadmin', '#unsubscribe_message_success');
                    } else {
                        $message = AmosAdmin::t('amosadmin', '#unsubscribe_message_error');
                    }
                } else {
                    $message = AmosAdmin::t('amosadmin', '#unsubscribe_message_notify_module_not_present');
                }
            } else {
                $message = AmosAdmin::t('amosadmin', '#unsubscribe_message_invalid_token');
            }
        }
        return $this->render('unsubscribe', [
            'message' => $message
        ]);
    }

    /**
     *  Action to disable notifications
     * @param $token
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDisableNotifications($token)
    {
        $this->setUpLayout('login');
        $appName = \Yii::$app->name;
        $user = User::find()->andWhere(new Expression("MD5(CONCAT(user.id, '" . $appName . "', user.username)) = '" . $token . "'"))->one();
        if (empty($user)) {
            return $this->render('security-message',
                [
                    'title_message' => AmosAdmin::t('amosadmin', 'Errore'),
                    'result_message' => AmosAdmin::t('amosadmin', '#invalid_token')
                ]);
        }
        if (\Yii::$app->request->isPost) {
            /** @var \open20\amos\notificationmanager\AmosNotify $notifyModule */
            $notifyModule = \Yii::$app->getModule('notify');
            if (!empty($notifyModule)) {
                /** @var  $userProfile UserProfile */
                $userProfile = $user->userProfile;
                $userProfile->notify_from_editorial_staff = 0;
                $userProfile->save(false);
                $ok = $notifyModule->saveNotificationConf($user->id,
                    \open20\amos\notificationmanager\models\NotificationsConfOpt::EMAIL_OFF);
                if ($ok) {
                    $result_message = AmosAdmin::t('amosadmin', '#disable_notification_message_success');
                    $titleMessage = AmosAdmin::t('amosadmin', '#disable_notification_title_success');
                } else {
                    $result_message = AmosAdmin::t('amosadmin', '#disable_notification_message_error');
                    $titleMessage = AmosAdmin::t('amosadmin', '#disable_notification_title_error');
                }
                return $this->render('security-message',
                    [
                        'title_message' => $titleMessage,
                        'result_message' => $result_message
                    ]);
            }
        }

        return $this->render('disable_notifications',
            [
                'model' => $user,
                'token' => $token
            ]);
    }

    public function shibbolethAuthentication()
    {
        pr(Yii::$app->request->get(), 'get');
        pr(Yii::$app->request->post(), 'post');
        pr(Yii::$app->request->params, 'params');
        pr(Yii::$app->request->cookies, 'cookies');
        pr(Yii::$app->request->headers, 'headers');
        die;
        //
    }

    public static function shibbolethHeaderParse()
    {
        pr(Yii::$app->request->get(), 'get');
        pr(Yii::$app->request->post(), 'post');
        pr(Yii::$app->request->params, 'params');
        pr(Yii::$app->request->cookies, 'cookies');
        pr(Yii::$app->request->headers, 'headers');
        //
    }

    /**
     * @param UserProfile $userProfile
     * @param Profile $socialProfile
     * @param $provider
     * @return bool|SocialAuthUsers
     */
    protected function createSocialUser($userProfile, $socialProfile, $provider)
    {
        try {
            /**
             * @var $socialUser \open20\amos\socialauth\models\SocialAuthUsers
             */
            $socialUser = new \open20\amos\socialauth\models\SocialAuthUsers();

            /**
             * @var $socialProfileArray array User profile from provider
             */
            $socialProfileArray = (array)$socialProfile;
            $socialProfileArray['provider'] = $provider;
            $socialProfileArray['user_id'] = $userProfile->user_id;

            /**
             * If all data can be loaded to new record
             */
            if ($socialUser->load(['SocialAuthUsers' => $socialProfileArray])) {
                /**
                 * Is valid social user
                 */
                if ($socialUser->validate()) {
                    $socialUser->save();

                    Yii::$app->session->addFlash('success',
                        AmosAdmin::t('amosadmin', 'Social Account for {provider} Linked to your User',
                            [
                                'provider' => $provider
                            ]));

                    return $socialUser;
                } else {
                    Yii::$app->session->addFlash('danger',
                        Module::t('amossocialauth', 'Unable to Link The Social Profile'));
                    return false;
                }
            } else {
                Yii::$app->session->addFlash('danger', Module::t('amossocialauth', 'Invalid Social Profile, Try again'));
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     *
     * @return type
     */
    public function actionCheckSessionScope()
    {
        $retValue = '';

        $module = Yii::$app->getModule('cwh');
        if (!is_null($module)) {
            $scope = $module->getCwhScope();
            $retValue = isset($scope['community']) ? $scope['community'] : '';
        }
        return $retValue;
    }

    /**
     *
     */
    public function actionResetDashboardByScope()
    {
        $url = '/dashboard';

        $module = Yii::$app->getModule('cwh');
        if (!is_null($module)) {
            $scope = $module->getCwhScope();
            isset($scope['community']) ? '/community/join?id=' . $scope['community'] : '/dashboard';
        }
        $this->redirect(Url::to($url));
    }

    /**
     * @param $model LoginForm
     * @param $token
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function loginByToken($model, $token)
    {
        $authTimeout = 3600 * 24 * 30;
        if (!empty(\Yii::$app->user->authTimeout)) {
            $authTimeout = \Yii::$app->user->authTimeout;
        }
        $tokenUser = TokenUsers::find()->andWhere(['token' => $token])->one();
        /** @var $tokenUser TokenUsers */
        if ($tokenUser) {
            if (!$tokenUser->isTokenExpired() && !$tokenUser->hasExceededAccess()) {
                /** @var  $user User */
                $user = $tokenUser->user;
                $model->username = $user->username;
                Yii::$app->getUser()->setReturnUrl($tokenUser->tokenGroup->url_redirect);
                if (Yii::$app->user->login($user, $model->rememberMe ? $authTimeout : 0)) {
                    if ($tokenUser->used == 0 || !(!empty(\Yii::$app->params['performance']) && \Yii::$app->params['performance'] == true)) {
                        $tokenUser->used = $tokenUser->used + 1;
                        $tokenUser->save();
                    }
                    return $this->redirect($tokenUser->tokenGroup->url_redirect);
                }
            } else {
                \Yii::$app->session->addFlash('warning', AmosAdmin::t('amosadmin', 'Token expired'));
            }
        }
    }
    
    public function actionSetDlSemplificationModalCookie()
    {
        if (!\Yii::$app->request->isAjax || !\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException(Yii::t('amoscore', 'Non sei autorizzato a visualizzare questa pagina'));
        }
        $expireDate = new \DateTime('2021-09-30 23:59:59');
        $cookie = new Cookie([
            'name' => 'dl_semplification_modal_cookie',
            'value' => '1',
            'expire' => $expireDate->getTimestamp()
        ]);
        Yii::$app->getResponse()->getCookies()->add($cookie);
        return true;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionReconciliation($done = false)
    {
        $model = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
        if (!$done) {
            return $this->render('reconciliation', ['model' => $model]);
        }
        \Yii::$app->session->addFlash('success', AmosAdmin::t('amosadmin', "La procedura di associazione dei dati legati all'utenza con l’identità digitale è completata. Da oggi potrai accedere attraverso il sistema di Identità Digitale"));
        return $this->redirect('/');
    }
}

?>
