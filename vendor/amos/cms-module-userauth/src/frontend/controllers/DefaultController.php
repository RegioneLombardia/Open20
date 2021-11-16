<?php

namespace amos\userauth\frontend\controllers;

use amos\userauth\frontend\Module;
use amos\userauth\models\UserLoginForm;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\ForgotPasswordForm;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\Community;
use open20\amos\community\models\RegisterForm;
use open20\amos\core\interfaces\InvitationExternalInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\core\user\User;
use amos\userauth\models\FirstAccessForm;
use luya\cms\menu\QueryOperatorFieldInterface;
use luya\Config;
use luya\helpers\Url;
use luya\web\Controller;
use Yii;
use yii\filters\HttpCache;
use yii\helpers\Html;
use yii\web\Response;

class DefaultController extends Controller {

    public $module;
    public $adminModule;

    public function init() {
        parent::init();
        $this->module = Module::instance();
        $this->adminModule = AmosAdmin::instance();
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors() {
        return [
            'httpCache' => [
                'class' => HttpCache::class,
                'cacheControlHeader' => 'no-store, no-cache',
                'lastModified' => function ($action, $params) {
                    return time();
                },
            ],
        ];
    }

    /**
     * Render the login form model.
     *
     * @return Response|string
     */
    public function actionIndex($redir = null) {
        if (!CurrentUser::isPlatformGuest()) {
            if(is_null($redir)){
                return $this->goHome();
            }else{
                return $this->redirect($this->getRedirectUrl($redir));
            }
        }

        $model = new UserLoginForm();
        $model->adminModule = $this->adminModule; 

        if ($model->load(Yii::$app->request->post()) && $model->validate() && Yii::$app->user->login($model->user, $model->rememberme ? $this->module->remember_length : 0)) {
            if(\Yii::$app instanceof \open20\amos\core\applications\CmsApplication)
            {
                \Yii::$app->session->set('access_token', \Yii::$app->getUser()->identity->getAccessToken());
                
            }
            $to_go = null;
            if (!empty($redir)) {
                $to_go = $this->redirect($this->getRedirectUrl($redir));
            } elseif (!empty(Yii::$app->user->getReturnUrl())) {
                $to_go = $this->redirect($this->getRedirectUrl(Yii::$app->user->getReturnUrl()));
            }
            return $to_go;
        }

        if (isset(\Yii::$app->params['linkConfigurations']['registrationLinkCommon'])) {
            $registrationUrl = \Yii::$app->params['linkConfigurations']['registrationLinkCommon'];
        } else {
            $registrationUrl = Module::toUrlModule('/default/register');
        }

        if (isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon'])) {
            $loginUrl = \Yii::$app->params['linkConfigurations']['loginLinkCommon'];
        } else {
            $loginUrl = '/site/login';
        }

        if (isset(\Yii::$app->params['linkConfigurations']['forgotPasswordLinkCommon'])) {
            $forgotPwdUrl = \Yii::$app->params['linkConfigurations']['forgotPasswordLinkCommon'];
        } else {
            $forgotPwdUrl = Module::toUrlModule('/default/forgot-password');
        }


        return $this->render(
                        'bi-index',
                        [
                            'model' => $model,
                            'registrationUrl' => $registrationUrl,
                            'loginUrl' => $loginUrl,
                            'forgotPwdUrl' => $forgotPwdUrl,
                            'showLiteMode' => \Yii::$app->params['layoutConfigurations']['showLiteModeLogin'],
                            //'hideSpidButtonDescription' => \Yii::$app->params['layoutConfigurations']['hideSpidDescriptionLogin'],
                            'hideIdpcButtonInfo' => \Yii::$app->params['layoutConfigurations']['hideIdpcButtonInfo'],
                            
                        ]
        );
    }

    /**
     * Get the redirect url from config, redir parmater or default base (home) url.
     *
     * @param string $redir Optional urlencoded redirect from url
     * @return string
     */
    protected function getRedirectUrl($redir) {
        if (!empty($redir)) {
            return urldecode($redir);
        }

        $navId = Config::get(Module::USERAUTH_CONFIG_AFTER_LOGIN_NAV_ID, false);

        if ($navId) {
            $navItem = Yii::$app->menu->find()->where([QueryOperatorFieldInterface::FIELD_NAVID => $navId])->with([
                        'hidden'
                    ])->one();

            if ($navItem) {
                return $navItem->absoluteLink;
            }
        }

        return Url::base(true);
    }

    /**
     * @return bool|Response
     */
    public function actionRegister() {

        $this->setActionLayout();

        if (!CurrentUser::isPlatformGuest()) {
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
                        AmosAdmin::t('amosadmin',
                                $this->module->textWarningForRegisterDisabled));
            } else {
                Yii::$app->session->addFlash('danger',
                        AmosAdmin::t('amosadmin', 'Signup Disabled'));
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

        // Invitation User id
        $iuid = isset($getParams['iuid']) ? $getParams['iuid'] : null;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->beforeRegisterNewUser($model);
            /**
             * @var $newUser integer False or UserId
             */
            $newUser = $this->adminModule->createNewAccount(
                    $model->nome, $model->cognome, $model->email, $model->privacy,
                    false, null, \Yii::$app->request->post('redirectUrl')
            );

            /**
             * If $newUser is false the user is not created
             */
            if (!$newUser || isset($newUser['error'])) {
                $result_message = [];
                $errorMail = ($model->email ? $model->email : '');
                array_push($result_message,
                        AmosAdmin::t('amosadmin', '#error_register_user',
                                ['errorMail' => $errorMail]));

                return $this->render('security-message',
                                [
                                    'title_message' => AmosAdmin::t('amosadmin',
                                            'Spiacenti'),
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
                                    'title_message' => AmosAdmin::t('amosadmin',
                                            'Errore'),
                                    'result_message' => AmosAdmin::t('amosadmin',
                                            'Error when loading profile data, try again'),
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
                    $this->createSocialUser($newUserProfile, $socialProfile,
                            $provider);
                }
            }

            $iuid = \Yii::$app->request->post('iuid');

            $communityId = \Yii::$app->request->post('community');
            $community = null;
            if (\Yii::$app->getModule('community')) {
                $community = Community::findOne($communityId);
            }

            if (!empty($getParams['moduleName']) && !empty($getParams['contextModelId'])) {
                /** @var AmosModule $module */
                $module = Yii::$app->getModule($getParams['moduleName']);
                if (!is_null($module) && ($module instanceof InvitationExternalInterface)) {
                    $okUserContextAssociation = $module->addUserContextAssociation($userId,
                            $getParams['contextModelId']);
                    if (!$okUserContextAssociation) {
                        Yii::$app->getSession()->addFlash('danger',
                                AmosAdmin::t('amosadmin',
                                        '#user_context_association_error'));
                    }
                }
            }

            $sent = UserProfileUtility::sendCredentialsMail($newUserProfile,
                            $community);

            if (!$sent) {
                return $this->render('security-message',
                                [
                                    'title_message' => AmosAdmin::t('amosadmin',
                                            '#error'),
                                    'result_message' => AmosAdmin::t('amosadmin',
                                            '#error_send_register_mail')
                ]);
            } else {
                // Sent notification email to invitation user
                if ($iuid != null) {
                    $sent = UserProfileUtility::sendUserAcceptRegistrationRequestMail($newUserProfile,
                                    $community, $iuid);
                }

                return $this->render('security-message',
                                [
                                    'title_message' => AmosAdmin::t('amosadmin',
                                            '#msg_complete_registration_title'),
                                    'result_message' => [
                                        AmosAdmin::t('amosadmin',
                                                '#msg_complete_registration_result_1') . '<br>' . Html::tag('span',
                                                $model->email),
                                        AmosAdmin::t('amosadmin',
                                                '#msg_complete_registration_result_2')
                                    ]
                ]);
            }

            //return $this->goHome();
        }

        if (isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon'])) {
            $loginUrl = \Yii::$app->params['linkConfigurations']['loginLinkCommon'];
        } else {
            $loginUrl = '/site/login';
        }

        return $this->render(
                        'register',
                        [
                            'model' => $model,
                            'iuid' => $iuid,
                            'loginUrl' => $loginUrl,
        ]);
    }

    /**
     * @param RegisterForm $model
     */
    protected function beforeRegisterNewUser($model) {
        
    }

    /**
     * @param RegisterForm $model
     * @param UserProfile $userProfile
     */
    protected function afterRegisterNewUser($model, $userProfile) {
        
    }

    /**
     * Forgotten password form
     * @return string|Response
     */
    public function actionForgotPassword() {

        $this->setActionLayout();
        
        if (!CurrentUser::isPlatformGuest()) {
            return $this->goHome();
        }

        /** @var ForgotPasswordForm $userProfileModel */
        $model = $this->adminModule->createModel('ForgotPasswordForm');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->email != NULL) {
                $dati_utente = $model->verifyEmail($model->email);
                if ($dati_utente) {
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
                                        AmosAdmin::t('amosadmin', '#msg_forgot_pwd_result_1') . '<br>' . Html::tag('span', $model->email),
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
    protected function actionSpedisciCredenziali($id, $isForgotPasswordView = false, $isForgotPasswordRequest = false, $urlCurrent = null) {
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
     * Login-info choice at register step
     * @return string
     */
    public function actionInsertAuthData() {
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
            $isMail = ((isset($assistance['type']) && $assistance['type'] == 'email') || (!isset($assistance['type']) && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
            $mailAddress = isset($assistance['email']) ? $assistance['email'] : (isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : '');
            $linkHref = $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : '');
            if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))) {
                $tokenErrorMessage .= Html::tag('br') .
                        AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_contact_assistance") . ' ' .
                        Html::a(
                                AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"), $linkHref,
                                ['title' => Yii::t('amoscore', 'Verrà aperta una nuova finestra')]
                        ) . Html::tag('br') . AmosAdmin::t('amosadmin',
                                "#insert_auth_data_token_expired_message_forgot_password_else") . ' ' .
                        Html::a(
                                AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"),
                                ['/' . Module::getModuleName() . '/default/forgot-password'],
                                ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link')]
                );
            } else {
                $tokenErrorMessage .= Html::tag('br') .
                        AmosAdmin::t('amosadmin', "#forgot_password_title_link") . ' ' .
                        Html::a(
                                AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"),
                                ['/' . Module::getModuleName() . '/default/forgot-password'],
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
     * Login function called in case of automatic login needs.
     * @param string $usernameOrEmail
     * @param string $password
     * @param int|null $community_id
     * @param string|null $postLoginUrl
     * @return string|Response
     * @throws \Exception
     */
    protected function login($usernameOrEmail, $password, $community_id = null, $postLoginUrl = null, $isFirstAccess = false) {
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

        if ($model->login()) {
            
            if(\Yii::$app instanceof \open20\amos\core\applications\CmsApplication)
            {
                \Yii::$app->session->set('access_token', \Yii::$app->getUser()->identity->getAccessToken());
                
            }
            
            /* per amos */
            if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']) {
                $ruolo = \Yii::$app->authManager->getRole($model->ruolo);
                $userId = \Yii::$app->getUser()->getId();
                \Yii::$app->authManager->revokeAll($userId);
                \Yii::$app->authManager->assign($ruolo, $userId);
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
            if (isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon'])) {
                $loginUrl = \Yii::$app->params['linkConfigurations']['loginLinkCommon'];
            } else {
                $loginUrl = '/site/login';
            }
            return $this->redirect(Yii::$app->getUrlManager()->createUrl([$loginUrl]));
        }
    }

    /**
     * 
     */
    protected function setActionLayout()
    {
        if(!is_null($this->module->viewLayout))
        {
            $this->layout = $this->module->viewLayout;
        }
    }
}
