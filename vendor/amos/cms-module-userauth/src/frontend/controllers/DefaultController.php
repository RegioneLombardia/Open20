<?php

namespace amos\userauth\frontend\controllers;

use amos\userauth\frontend\Module;
use amos\userauth\frontend\utility\CmsUserauthUtility;
use amos\userauth\models\CredentialRequestForm;
use amos\userauth\models\FirstAccessForm;
use amos\userauth\models\UserLoginForm;
use open20\amos\core\applications\CmsApplication;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\ForgotPasswordForm;
use open20\amos\admin\models\RegisterForm;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\Community;
use open20\amos\core\interfaces\InvitationExternalInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\invitations\models\Invitation;
use open20\amos\socialauth\utility\SocialAuthUtility;
use Hybridauth\User\Profile;
use luya\cms\menu\QueryOperatorFieldInterface;
use luya\admin\models\Config;
use luya\helpers\Url;
use luya\web\Controller;
use Yii;
use yii\filters\HttpCache;
use yii\helpers\Html;
use yii\web\Response;

/**
 * Class DefaultController
 * @package amos\userauth\frontend\controllers
 */
class DefaultController extends Controller
{
    /**
     * @var Module $module
     */
    public $module;
    /**
     * 
     * @var type
     */
    public $adminModule;

    /**
     * 
     */
    public function init()
    {
        parent::init();
        $this->module      = Module::instance();
        $this->adminModule = AmosAdmin::instance();
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
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
    public function actionIndex($redir = null)
    {
        if (!CurrentUser::isPlatformGuest()) {
            $current = \Yii::$app->menu->current;
            if (!empty($current) && !empty($current->itemArray['is_home']) && $current->itemArray['is_home'] == '1') {
                return;
            }
            if (is_null($redir)) {
                return $this->goHome();
            } else {
                return $this->redirect($this->getRedirectUrl($redir));
            }
        }

        $model              = new UserLoginForm();
        $model->adminModule = $this->adminModule;

        $this->beforeUserLogin($model);

        if (\Yii::$app->request->get('redirectToRegisterUrl')) {
            \Yii::$app->session->set(
                'redirect_url_spid',
                \Yii::$app->request->get('redirectToRegisterUrl')
            );
        }

        //if you do reconciliation set redirect url of reconciliation-thank-you
        $spidData = \Yii::$app->session->get('IDM');
        if ($spidData && !empty($_POST['associateSpid'])) {
            Yii::$app->getUser()->setReturnUrl('/userauthfrontend/default/reconciliation-thank-you');
        }

        $authTimeout = 3600 * 24 * 30;
        if (!empty(\Yii::$app->user->authTimeout)) {
            $authTimeout = \Yii::$app->user->authTimeout;
        }
        
        if (
            $model->load(Yii::$app->request->post())
            && $model->validate()
            && Yii::$app->user->login($model->user, $model->rememberme ? $authTimeout : 0)
        ) {
            //DL Semplification
            /** @var \open20\amos\socialauth\Module $socialModule */
            $socialModule = Yii::$app->getModule('socialauth');

            if ($this->module->enableDlSemplificationLight) {
                $spidData = \Yii::$app->session->get('IDM');
                if (!empty($model->user) && $spidData && !empty($_POST['associateSpid'])) {
                    $createdSpidUser = SocialAuthUtility::createIdmUser($spidData, $model->user->id);
                    Yii::$app->session->addFlash('success', 'Utente riconciliato correttamente');
                }
            } else if ($this->adminModule->enableDlSemplification && !is_null($socialModule) && !CmsUserauthUtility::isAccessPermitted()) {
                /** @var \open20\amos\socialauth\models\SocialIdmUser $socialIdmUser */
                $socialIdmUser = $socialModule->findSocialIdmByUserId($model->user->id);
                if (!is_null($socialIdmUser)) {
                    Yii::$app->user->logout();
                    Yii::$app->session->addFlash('danger',
                        AmosAdmin::t('amosadmin', '#dl_semplification_cannot_login_with_basic_auth'));
                    return $this->goHome();
                }
            }

            $this->afterUserLogin($model);
            if (\Yii::$app instanceof CmsApplication) {
                \Yii::$app->session->set(
                    'access_token',
                    \Yii::$app->getUser()->identity->getAccessToken()
                );
            }
            
            $to_go = null;
            if (!empty($redir)) {
                $to_go = $this->redirect($this->getRedirectUrl($redir));
            } else {
                $to_go = $this->redirect($this->getRedirectUrl(""));
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

        if (CmsUserauthUtility::isAccessPermitted() && !\Yii::$app->request->get('reconciliation')) {
            \Yii::$app->params['showLoginStandard'] = true;
        }

        $viewToRender = 'bi-index'.(($this->adminModule->enableDlSemplification || $this->module->enableDlSemplificationLight)
                ? '_dl_semplification' : '');
        return $this->render(
                $viewToRender,
                [
                'model' => $model,
                'registrationUrl' => $registrationUrl,
                'loginUrl' => $loginUrl,
                'forgotPwdUrl' => $forgotPwdUrl,
                'showLiteMode' => \Yii::$app->params['layoutConfigurations']['showLiteModeLogin'],
                'hideIdpcButtonInfo' => \Yii::$app->params['layoutConfigurations']['hideIdpcButtonInfo'],
                ]
        );
    }

    /**
     * Render the login form model.
     *
     * @return Response|string
     */
    public function actionLoginAdmin($redir = null)
    {
        if (!CurrentUser::isPlatformGuest()) {
            $current = \Yii::$app->menu->current;
            if (!empty($current) && !empty($current->itemArray['is_home']) && $current->itemArray['is_home'] == '1') {
                return;
            }
            if (is_null($redir)) {
                return $this->goHome();
            } else {
                return $this->redirect($this->getRedirectUrl($redir));
            }
        }

        $model              = new UserLoginForm();
        $model->adminModule = $this->adminModule;

        $this->beforeUserLogin($model);


        if (\Yii::$app->request->get('redirectToRegisterUrl')) {
            \Yii::$app->session->set('redirect_url_spid', \Yii::$app->request->get('redirectToRegisterUrl'));
        }

        //if you do reconciliation set redirect url of reconciliation-thank-you
        $spidData = \Yii::$app->session->get('IDM');
        if ($spidData && !empty($_POST['associateSpid'])) {
            Yii::$app->getUser()->setReturnUrl('/userauthfrontend/default/reconciliation-thank-you');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && Yii::$app->user->login($model->user,
                $model->rememberme ? $this->module->remember_length : 0)) {
            //DL Semplification
            /** @var \open20\amos\socialauth\Module $socialModule */
            $socialModule = Yii::$app->getModule('socialauth');

            if ($this->module->enableDlSemplificationLight) {
                $spidData = \Yii::$app->session->get('IDM');
                if (!empty($model->user) && $spidData && !empty($_POST['associateSpid'])) {
                    $createdSpidUser = SocialAuthUtility::createIdmUser($spidData, $model->user->id);
                    Yii::$app->session->addFlash('success', 'Utente riconciliato correttamente');
                }
            } else if ($this->adminModule->enableDlSemplification && !is_null($socialModule)) {
                /** @var SocialIdmUser $socialIdmUser */
                $socialIdmUser = $socialModule->findSocialIdmByUserId($model->user->id);
                if (!is_null($socialIdmUser)) {
                    Yii::$app->user->logout();
                    Yii::$app->session->addFlash('danger',
                        AmosAdmin::t('amosadmin', '#dl_semplification_cannot_login_with_basic_auth'));
                    return $this->goHome();
                }
            }

            $this->afterUserLogin($model);
            if (\Yii::$app instanceof \open20\amos\core\applications\CmsApplication) {
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

        if (CmsUserauthUtility::isAccessPermitted() && !\Yii::$app->request->get('reconciliation')) {
            \Yii::$app->params['showLoginStandard'] = true;
        }

        $viewToRender = 'bi-index-admin'.(($this->adminModule->enableDlSemplification || $this->module->enableDlSemplificationLight)
                ? '_dl_semplification' : '');
        return $this->render(
            $viewToRender,
            [
                'model' => $model,
                'registrationUrl' => $registrationUrl,
                'loginUrl' => $loginUrl,
                'forgotPwdUrl' => $forgotPwdUrl,
                'showLiteMode' => \Yii::$app->params['layoutConfigurations']['showLiteModeLogin'],
                'hideIdpcButtonInfo' => \Yii::$app->params['layoutConfigurations']['hideIdpcButtonInfo'],
            ]
        );
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCredentialRequest()
    {
        $this->setActionLayout();
        $model = new CredentialRequestForm();

        if ($this->module->enableCredentialRequest){
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                    return $this->redirect(['/login']);
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
                }
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Attualmente, 
            la funzionalità di richiesta delle credenziali è disabilitata.'));
        }

        return $this->render('credential-request', ['model' => $model]);
    }

    /**
     * Operations
     * @param $model
     */
    protected function beforeUserLogin($model)
    {

    }

    protected function afterUserLogin($model)
    {

    }

    /**
     * Get the redirect url from config, redir parmater or default base (home) url.
     *
     * @param string $redir Optional urlencoded redirect from url
     * @return string
     */
    protected function getRedirectUrl($redir)
    {
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
     * @return bool|\yii\web\Response
     */
    public function actionRegister()
    {
        return $this->register();
    }

    /**
     * @return bool|\yii\web\Response
     */
    public function actionRegisterWithCode()
    {
        return $this->register('register_with_code');
    }

    /**
     * @param string $registerView
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function register($registerView = 'register')
    {
        $this->setActionLayout();
        if (!CurrentUser::isPlatformGuest()) {
            Yii::$app->session->set('removeAfterLogout', 'true');
            Yii::$app->user->logout();
            Yii::$app->session->remove('removeAfterLogout');
        }

        /**
         * If signup is not enabled
         * */
        if (!$this->module->enableRegister && !(($this->module->enableRegisterContext == true) && !empty($this->module->enableRegisterModuleName) && !empty(\Yii::$app->getModule($this->module->enableRegisterModuleName)) && filter_input(INPUT_GET, 'contextModelId'))) {
            if (!empty($this->module->textWarningForRegisterDisabled)) {
                Yii::$app->session->addFlash('warning',
                    AmosAdmin::t('amosadmin', $this->module->textWarningForRegisterDisabled));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Signup Disabled'));
            }
            if ($this->module->redirectLinkInRegister) {
                return $this->redirect($this->module->redirectLinkInRegister);
            } else {
                return $this->goHome();
            }
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

        $socialAccount = false;

        // if($this->module->enableOverrideSPIDemail && isset($getParams['email'])) {
        //     \Yii::$app->session->set('customEmail', $getParams['email']);
        // }
        //
        // if($this->module->enableOverrideSPIDemail && \Yii::$app->session->get('customEmail') != null) {
        //     $spidData['emailAddress'] = \Yii::$app->session->get('customEmail');
        // }

        if (!empty($spidData)) {
            $model->nome    = $spidData['nome'];
            $model->cognome = $spidData['cognome'];
            $socialAccount  = true;

            if ($this->module->enableOverrideSPIDemail && \Yii::$app->session->get('custom-spid-email') != null) {
                $model->email = \Yii::$app->session->get('custom-spid-email');
            } else {
                $model->email = $spidData['emailAddress'];
            }
        } elseif (!empty($getParams['name']) && !empty($getParams['surname']) && !empty($getParams['email'])) {
            $model->nome    = $getParams['name'];
            $model->cognome = $getParams['surname'];
            $model->email   = $getParams['email'];

            if ($this->module->enableOverrideSPIDemail) {
                \Yii::$app->session->set('custom-spid-email', $getParams['email']);
            }
        } elseif ($socialProfile && $socialProfile->email) {
            $model->nome    = $socialProfile->firstName;
            $model->cognome = $socialProfile->lastName;
            $model->email   = $socialProfile->email;
            $socialAccount  = true;
        }

        // Used for external invitation registrations
        if (!empty($getParams['moduleName']) && !empty($getParams['contextModelId'])) {
            $model->moduleName     = $getParams['moduleName'];
            $model->contextModelId = $getParams['contextModelId'];
        }

        if ($this->adminModule->enableDlSemplification && !$spidData) {
            if (!empty($this->adminModule->textWarningForRegisterDisabled)) {
                Yii::$app->session->addFlash('warning',
                    AmosAdmin::t('amosadmin', $this->adminModule->textWarningForRegisterDisabled));
            } else {
                Yii::$app->session->addFlash('danger', AmosAdmin::t('amosadmin', 'Signup Disabled'));
            }
    
            if ($this->module->redirectLinkInRegister) {
                return $this->redirect($this->module->redirectLinkInRegister);
            } else {
                return $this->goHome();
            }
        }

        // Invitation User id
        $iuid = isset($getParams['iuid']) ? $getParams['iuid'] : null;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {


            $this->beforeRegisterNewUser($model);
            /**
             * @var $newUser integer False or UserId
             */
            $newUser = $this->adminModule->createNewAccount(
                $model->nome, $model->cognome, $model->email, $model->privacy, false, null,
                \Yii::$app->request->post('redirectUrl')
            );

            /**
             * If $newUser is false the user is not created
             */
            if (!$newUser || isset($newUser['error'])) {
                $result_message = [];
                $errorMail      = ($model->email ? $model->email : '');
                array_push($result_message,
                    AmosAdmin::t('amosadmin', '#error_register_user', ['errorMail' => $errorMail]));

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
            $newUserProfile   = $userProfileModel::findOne(['user_id' => $userId]);

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
            //Social Auth trigger
            $socialModule = Yii::$app->getModule('socialauth');

            //If the module is enabled then create social user
            if (!empty($socialModule)) {
                //Provider is in session
                $provider = Yii::$app->session->get('social-pending');

                //If is set social match i nett to link user
                if ($provider) {
                    $this->createSocialUser($newUserProfile, $socialProfile, $provider);
                }

                $spidData = \Yii::$app->session->get('IDM');
                if (!empty($newUserProfile->user) && $spidData) {
                    $createdSpidUser = SocialAuthUtility::createIdmUser($spidData, $newUserProfile->user_id);
                }
            }

            $this->afterRegisterNewUser($model, $newUserProfile);

            $iuid = \Yii::$app->request->post('iuid');

            $communityId = \Yii::$app->request->post('community');
            $community   = null;
            if (\Yii::$app->getModule('community')) {
                $community = Community::findOne($communityId);
            }

            if (!empty($model->moduleName) && !empty($model->contextModelId)) {
                /** @var AmosModule $module */
                $module = Yii::$app->getModule($model->moduleName);
                if (!is_null($module) && ($module instanceof InvitationExternalInterface)) {
                    $this->beforeAddUserContextAssociation($model, $newUserProfile);
                    $okUserContextAssociation = $module->addUserContextAssociation($userId, $model->contextModelId);
                    $this->afterAddUserContextAssociation($model, $newUserProfile, $okUserContextAssociation);
                    if (!$okUserContextAssociation) {
                        Yii::$app->getSession()->addFlash('danger',
                            AmosAdmin::t('amosadmin', '#user_context_association_error'));
                    }
                }
            }

            $sent = UserProfileUtility::sendCredentialsMail($newUserProfile, $community, null, $socialAccount);

            if (!$sent) {
                return $this->render('security-message',
                        [
                        'title_message' => AmosAdmin::t('amosadmin', '#error'),
                        'result_message' => AmosAdmin::t('amosadmin', '#error_send_register_mail')
                ]);
            } else {
                // Sent notification email to invitation user
                if ($iuid != null) {
                    $sent = UserProfileUtility::sendUserAcceptRegistrationRequestMail($newUserProfile, $community, $iuid);
                }
                $moduleInvitation = \Yii::$app->getModule('invitations');
                if ($moduleInvitation) {
                    $invitation = Invitation::findOne(\Yii::$app->request->get('iid'));
                    if ($invitation) {
                        if ($invitation->hasProperty('invite_accepted')) {
                            $invitation->invite_accepted = true;
                            $invitation->save(false);
                        }
                    }
                }

                $this->operationsAfterSave();

                $thankyou_message =
                    Module::t('Gentile {nome} {cognome}', [
                        'nome' => $newUserProfile->nome,
                        'cognome' => $newUserProfile->cognome,
                    ]).
                    "<br>"
                    .Module::t('Grazie per aver effettuato la registrazione alla piattaforma {appname}.',
                        [
                        'appname' => \Yii::$app->name
                ]);

                $msg1 = '#msg_complete_registration_result_1';
                $msg2 = '#msg_complete_registration_result_2';
                if ($this->adminModule->enableDlSemplification || $spidData) {
                    $msg1 .= '_dl_semplification';
                    $msg2 .= '_dl_semplification';
                } elseif ($socialAccount) {
                    $msg1 .= '_social_registration';
                    $msg2 .= '_social_registration';
                }

                return $this->render('security-message',
                        [
                        'title_message' => AmosAdmin::t('amosadmin', '#msg_complete_registration_title'),
                        'result_message' => [
                            $thankyou_message."<br>".AmosAdmin::t('amosadmin', $msg1).'<br>'.Html::tag('span',
                                Html::encode($model->email)),
                            AmosAdmin::t('amosadmin', $msg2)
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

        $viewToRender = $registerView.(($this->adminModule->enableDlSemplification || $this->module->enableDlSemplificationLight)
                ? '_dl_semplification' : '');
        return $this->render($viewToRender,
                [
                'model' => $model,
                'iuid' => $iuid,
                'loginUrl' => $loginUrl,
                'codiceFiscale' => ($this->adminModule->enableDlSemplification && $spidData && $spidData['codiceFiscale']
                    ? $spidData['codiceFiscale'] : null)
        ]);
    }

    /**
     * @param RegisterForm $model
     * @param UserProfile $userProfile
     */
    protected function beforeAddUserContextAssociation($model, $userProfile)
    {

    }

    /**
     * @param RegisterForm $model
     * @param UserProfile $userProfile
     * @param bool $okUserContextAssociation
     */
    protected function afterAddUserContextAssociation($model, $userProfile, $okUserContextAssociation)
    {

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
     * @param UserProfile $userProfile
     * @param Profile $socialProfile
     * @param $provider
     * @return bool|\open20\amos\socialauth\models\SocialAuthUsers
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
            $socialProfileArray             = (array) $socialProfile;
            $socialProfileArray['provider'] = $provider;
            $socialProfileArray['user_id']  = $userProfile->user_id;
            
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
                        \open20\amos\socialauth\Module::t('amossocialauth', 'Unable to Link The Social Profile'));
                    return false;
                }
            } else {
                Yii::$app->session->addFlash('danger', \open20\amos\socialauth\Module::t('amossocialauth', 'Invalid Social Profile, Try again'));
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Forgotten password form
     * @return string|Response
     */
    public function actionForgotPassword()
    {

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
                    $urlCurrent      = null;
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
                            AmosAdmin::t('amosadmin', '#msg_forgot_pwd_result_1').'<br>'.Html::tag('span', $model->email),
                            AmosAdmin::t('amosadmin', '#msg_forgot_pwd_result_2')
                        ],
                        'go_to_login_url' => !is_null(Yii::$app->getRequest()->get('return_url')) ? Yii::$app->getRequest()->get('return_url')
                            : Url::current(),
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
    protected function actionSpedisciCredenziali($id, $isForgotPasswordView = false, $isForgotPasswordRequest = false,
                                                 $urlCurrent = null)
    {
        /** @var UserProfile $userProfileModel */
        $userProfileModel = $this->adminModule->createModel('UserProfile');
        $model            = $userProfileModel::findOne($id);
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
    public function actionInsertAuthData()
    {
        $this->setActionLayout();
        $password_reset_token            = null;
        $user                            = null;
        $username                        = null;
        $community_id                    = null;
        $redirectUrl                     = \Yii::$app->getUser()->loginUrl;
        $precompileUsernameOnFirstAccess = $this->module->precompileUsernameOnFirstAccess;
        $isFirstAccess = false;
        
        $model = new FirstAccessForm();

        if (NULL !== (Yii::$app->getRequest()->getQueryParam('token'))) {
            $password_reset_token = Yii::$app->getRequest()->getQueryParam('token');
            $user                 = User::findByPasswordResetToken($password_reset_token);
            if ($user) {
                $username      = $user->username;
                $isFirstAccess = (empty($user->password_hash) && !$user->userProfile->privacy);
            }
            
            $model->setScenario(FirstAccessForm::RESET_PASSWORD);
        }

        $postLoginUrl = null;
        if (!is_null(Yii::$app->getRequest()->get('url_previous'))) {
            $postLoginUrl = Yii::$app->getRequest()->get('url_previous');
        }

        if ((Yii::$app->getRequest()->get('community_id')) !== NULL) {
            $community_id = Yii::$app->getRequest()->getQueryParam('community_id');
        }

        if ($user && !$username) {
            if (Yii::$app->request->isPost) {
                if ($isFirstAccess && is_null($user->userProfile->privacy)) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                }

                if (($model->load(Yii::$app->request->post())) && $model->validate()) {
                    
                    
                    if ($model->verifyUsername($model->username)) {
                        Yii::$app->getSession()->addFlash('danger',
                            Yii::t('amosadmin',
                                'Attenzione! La username inserita &egrave; gi&agrave; in uso. Sceglierne un&#39;altra.'));
                        return $this->render('first_access', [
                            'model' => $model,
                            'isFirstAccess' => $isFirstAccess
                                && is_null($user->userProfile->privacy)
                        ]);
                    } else {
                        $user->setPassword($model->password);
                        $user->username = $model->username;
                        if ($user->validate() && $user->save()) {
                            Yii::$app->getSession()->addFlash(
                                'success',
                                Yii::t('amosadmin', 'Perfetto! Hai scelto correttamente le tue credenziali.')
                            );
                            $user->removePasswordResetToken();
                            $user->save();
                            if ($isFirstAccess) {
                                $profile          = $user->userProfile;
                                $profile->privacy = 1;
                                $profile->save(false);
                            }
                            return $this->login($model->username, $model->password, $community_id, $postLoginUrl,
                                    $isFirstAccess);
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
                    return $this->render('first_access', [
                        'model' => $model,
                        'isFirstAccess' => $isFirstAccess
                    ]);
                }
            } else {
                if ($precompileUsernameOnFirstAccess) {
                    $model->username = $user->email;
                }
                if ($isFirstAccess) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                }
                $model->token = $password_reset_token;
                return $this->render('first_access', [
                    'model' => $model,
                    'isFirstAccess' => $isFirstAccess
                    && is_null($user->userProfile->privacy)
                ]);
            }
        } else if ($user && $username) {
            if (Yii::$app->request->isPost) {
                if ($isFirstAccess && is_null($user->userProfile->privacy)) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                } else {
                    $model->setScenario(FirstAccessForm::RESET_PASSWORD);
                }
                $model->username = $username;
                if (($model->load(Yii::$app->request->post())) && $model->validate()) {

                    $user->setPassword($model->password);

                    if ($user->validate() && $user->save()) {
                        Yii::$app->getSession()->addFlash(
                            'success',
                            Yii::t('amosadmin', 'Perfetto! Hai scelto correttamente la tua password.')
                        );
                        
                        $user->removePasswordResetToken();
                        $user->save();
                        if ($isFirstAccess) {
                            $profile          = $user->userProfile;
                            $profile->privacy = 1;
                            $profile->save(false);
                        }

                        return $this->login(
                            $username,
                            $model->password,
                            $community_id,
                            $postLoginUrl,
                            $isFirstAccess
                        );
                    } else {
                        return $this->render('security-message', [
                            'title_message' => AmosAdmin::t('amosadmin', 'Spiacenti'),
                            'result_message' => AmosAdmin::t('amosadmin', ' Errore! Il sito non ha risposto, probabilmente erano in corso operazioni di manutenzione. Riprova più tardi.')
                        ]);
                    }
                } else {
                    Yii::$app->getSession()->addFlash(
                            'danger',
                            Yii::t('amosadmin', 'Errore, contattare l\'amministratore della piattaforma.')
                        );
                    $model->token = $password_reset_token;
                    $model->username = $username;
                    
                    return $this->render('reset_password', [
                        'model' => $model,
                        'isFirstAccess' => $isFirstAccess
                            && is_null($user->userProfile->privacy)
                    ]);
                }
            } else {
                if ($isFirstAccess && is_null($user->userProfile->privacy)) {
                    $model->setScenario(FirstAccessForm::SCENARIO_CHECK_PRIVACY);
                }
                
                $model->token = $password_reset_token;
                $model->username = $username;
                
                return $this->render('reset_password', [
                    'model' => $model,
                    'isFirstAccess' => $isFirstAccess
                        && is_null($user->userProfile->privacy)
                ]);
            }
        } else {
            $tokenErrorMessage = AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message");

            // Pickup assistance params
            $assistance = isset(\Yii::$app->params['assistance'])
                ? \Yii::$app->params['assistance']
                : [];

            // Check if is in email mode
            $isMail = ((isset($assistance['type']) && $assistance['type'] == 'email') || (!isset($assistance['type']) && isset(\Yii::$app->params['email-assistenza']))) ? true : false;
            
            $mailAddress = isset($assistance['email']) ? $assistance['email'] : (isset(\Yii::$app->params['email-assistenza']) ? \Yii::$app->params['email-assistenza'] : '');
            
            $linkHref = $isMail ? 'mailto:' . $mailAddress : (isset($assistance['url']) ? $assistance['url'] : '');
            
            if ((isset($assistance['enabled']) && $assistance['enabled']) || (!isset($assistance['enabled']) && isset(\Yii::$app->params['email-assistenza']))) {
                $tokenErrorMessage .= Html::tag('br').
                    AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_contact_assistance").' '.
                    Html::a(
                        AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"), $linkHref,
                        ['title' => Yii::t('amoscore', 'Verrà aperta una nuova finestra')]
                    ).Html::tag('br').AmosAdmin::t('amosadmin',
                        "#insert_auth_data_token_expired_message_forgot_password_else").' '.
                    Html::a(
                        AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"),
                        ['/'.Module::getModuleName().'/default/forgot-password'],
                        ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link')]
                );
            } else {
                $tokenErrorMessage .= Html::tag('br').
                    AmosAdmin::t('amosadmin', "#forgot_password_title_link").' '.
                    Html::a(
                        AmosAdmin::t('amosadmin', "#insert_auth_data_token_expired_message_click_here"),
                        ['/'.Module::getModuleName().'/default/forgot-password'],
                        ['title' => AmosAdmin::t('amosadmin', '#forgot_password_title_link')]
                );
            }

            return $this->render('security-message', [
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
     * @param type $isFirstAccess
     * @return string|Response
     * @throws \Exception
     */
    protected function login(
        $usernameOrEmail,
        $password,
        $community_id = null,
        $postLoginUrl = null,
        $isFirstAccess = false
    )
    {
        /** @var LoginForm $model */
        $model           = $this->adminModule->createModel('LoginForm');
        $model->password = $password;
        if ($this->adminModule->allowLoginWithEmailOrUsername) {
            $model->usernameOrEmail = $usernameOrEmail;
            $user                   = User::findByUsernameOrEmail($model->usernameOrEmail);
        } else {
            $model->username = $usernameOrEmail;
            $user            = User::findByUsername($model->username);
        }

        if ($model->login()) {
            if (\Yii::$app instanceof CmsApplication) {
                \Yii::$app->session->set(
                    'access_token',
                    \Yii::$app->getUser()->identity->getAccessToken()
                );
            }

            /* per amos */
            if (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']) {
                $ruolo  = \Yii::$app->authManager->getRole($model->ruolo);
                $userId = \Yii::$app->getUser()->getId();
                \Yii::$app->authManager->revokeAll($userId);
                \Yii::$app->authManager->assign($ruolo, $userId);
            }

            if ($isFirstAccess && !is_null($user->userProfile->first_access_mail_url)) {
                $mailUrl                            = $user->userProfile->first_access_mail_url;
                $userProfile                        = $user->userProfile;
                $userProfile->first_access_mail_url = null;
                $userProfile->save(false);
                if (!is_null($postLoginUrl)) {
                    return $this->redirect($postLoginUrl);
                }
                return $this->redirect($mailUrl.'?user_id='.$user->id);
            } else if (!is_null($postLoginUrl)) {
                return $this->redirect($postLoginUrl);
            } else if ($community_id != null) {
                return $this->redirect(
                    Yii::$app->getUrlManager()->createUrl([
                        '/community/join',
                        'id' => $community_id,
                        'subscribe' => 1
                    ])
                );
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
        if (!is_null($this->module->viewLayout)) {
            $this->layout = $this->module->viewLayout;
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionReconciliationThankYou()
    {
        $this->setActionLayout();

        $model = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
        return $this->render('reconciliation-thank-you', ['model' => $model]);
    }

    /**
     * Empty function useful for overwriting controller (and this function) in poi-platform
     * without do changes to this controller.
     * @return bool
     */
    public function operationsAfterSave()
    {
        return true;
    }
}
