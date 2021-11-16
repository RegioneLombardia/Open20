<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth
 * @category   CategoryName
 */

namespace open20\amos\socialauth\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use open20\amos\socialauth\models\SocialIdmUser;
use open20\amos\socialauth\Module;
use open20\amos\socialauth\utility\SocialAuthUtility;
use Yii;
use yii\base\Action;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * Class ShibbolethController
 * @package open20\amos\socialauth\controllers
 */
class ShibbolethController extends BackendController
{
    const LOGGED_WITH_SPID_SESSION_ID = 'logged_with_spid_user_id';

    /**
     * @var string $layout
     */
    public $layout = 'login';

    /**
     * @var string $authType
     */
    protected $authType = '';

    /**
     * Questi sono metodi di accesso che non tengono conto del codice fiscale
     * Su questi metodi tutte le logiche sul codice fiscale non verranno prese in considerazione
     *
     * @var string[]
     */
    private $accessMethodsWithoutCF = [
        'EIDAS',
        'UTENTE',
    ];

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
                        'allow' => true,
                        'actions' => [
                            'endpoint',
                            'autologin',
                            'mobile',
                            'sign-up',
                            'set-module-instance',
                        ],
                        //'roles' => ['*']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'remove-spid',
                        ],
                        'roles' => ['@']
                    ]
                ],
            ],
        ];
    }

    /**
     *
     * @param Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {

        parent::init();
        $this->setUpLayout('login');
        // custom initialization code goes here
    }

    /**
     * Endpoint bridge with shibbolethsp authentication
     * @param bool $confirm
     * @return bool|\yii\web\Response
     */
    public function actionEndpoint($confirm = false)
    {
        //Link to current user with IDM
        return $this->tryIdmLink($confirm);
    }

    /**
     * Endpoint bridge with shibbolethsp authentication
     * @param bool $confirm
     * @return bool|\yii\web\Response
     */
    public function actionAutologin($confirm = false)
    {
        if(!Yii::$app->session->has('backTo')) {
            Yii::$app->session->set('backTo', \yii\helpers\Url::previous());
        }

        $result = $this->tryIdmLink(false, false, false);

        if (is_array($result) && isset($result['status'])) {
            $backTo = Yii::$app->session->get('backTo');
            Yii::$app->session->remove('backTo');

            if(in_array($result['status'], ['autoregistration', 'autologin'])) {
                $backTo = '/socialauth/shibboleth/endpoint';
            }

            return $this->render('autologin', ['backTo' => $backTo]);
        }

        return false;
    }
    
    /**
     * @return \yii\base\View|\yii\web\Response|\yii\web\View
     */
    public function actionMobile()
    {
        $result = $this->tryIdmLink(false, true, false);
        if (is_array($result) && isset($result['status']) || !$this->isGuestUser()) {
            if ($result['status'] == 'autoregistration') {
                return $this->redirect(['/admin/security/register', 'confirm' => true, 'from-shibboleth' => true, 'mobile' => true]);
            }
            $user = \open20\amos\mobile\bridge\modules\v1\models\User::findOne(Yii::$app->user->id);
            $user->refreshAccessToken('', '');
            return $this->redirect(['/socialauth/social-auth/land', 'token' => $user->getAccessToken()]);
        }
        return $this->view;
    }

    /**
     * @param bool $confirmLink
     * @param bool $render
     * @param bool $redirect
     * @return array|string|\yii\web\Response
     */
    public function tryIdmLink($confirmLink = false, $render = true, $redirect = true)
    {
        $procedure = $this->procedure($confirmLink, $render);
        $adminModule = AmosAdmin::getInstance();

        $urlRedirectPersonalized = \Yii::$app->session->get('redirect_url_spid');
        $urlRedirectPostReg = \Yii::$app->session->get('redirect_post_reg');
        if (!empty($urlRedirectPersonalized)) {
            //redirect personalized
            $redirect = true;
            \Yii::$app->session->remove('redirect_url_spid');
        } elseif (!empty($urlRedirectPostReg)) {
            //redirect personalized with autoregistration enabled
            if($procedure['status'] != 'autoregistration') {
                $urlRedirectPersonalized = $urlRedirectPostReg;
            }
            $redirect = true;
        }

        if (!is_array($procedure)) {
            if (!empty($urlRedirectPersonalized)) {
                return $this->redirect($urlRedirectPersonalized);
            }
            return $procedure;
        }
        // VarDumper::dump( $procedure, $depth = 10, $highlight = true); die;

        if ($redirect) {
            switch ($procedure['status']) {
                case 'success':
                case 'disabled-autologin':
                case 'rl':
                case 'fc':
                case 'ND':
                case 'override':
                case 'autologin':
                case 'conf':
                    {
                        if (!empty($urlRedirectPersonalized)) {
                            return $this->redirect($urlRedirectPersonalized);
                        }
                        Yii::debug("Login Status for {$procedure['user_id']} : {$procedure['status']}");
                        return $this->goBack();
                    }
                    break;
                case 'autoregistration':
                    if (!empty($urlRedirectPersonalized)) {
                        return $this->redirect($urlRedirectPersonalized);
                    }
                    return $this->redirect(['/' . $adminModule->id . '/security/register', 'confirm' => true, 'from-shibboleth' => true]);
                    break;
                case 'disabled':
                    \Yii::$app->session->set(self::LOGGED_WITH_SPID_SESSION_ID, $procedure['user_id']);
                    return $this->redirect(['/Shibboleth.sso/Logout', 'return' => Url::to('/' . $adminModule->id . '/login-info-request/activate-user?id=' . $procedure['user_id'], true)]);
                    break;
            }
        } else {
            return $procedure;
        }
    }

    /**
     * Controlli su utente guest - se possibile utilizzare CurrentUser::isPlatformGuest()
     */
    private function isGuestUser()
    {
        $isGuestUser = Yii::$app->user->isGuest;

        // baso il controllo sull'usare CurrentUser::isPlatformGuest sul paramtro
        if(isset(Yii::$app->params['platformConfigurations']['guestUserId'])) {
            $isGuestUser = \open20\amos\core\utilities\CurrentUser::isPlatformGuest();
        }

        return $isGuestUser;
    }
    
    /**
     * @param bool $confirmLink
     * @param bool $render
     * @return array|string|string[]
     * @throws \yii\base\InvalidConfigException
     */
    protected function procedure($confirmLink = false, $render = true)
    {
        //Store data into session
        $userDatas = $this->storeDataInSession();

        //Find for existing relation
        $relation = SocialIdmUser::findOne(['numeroMatricola' => $userDatas['matricola']]);
    
        /** @var Module $socialAuthModule */
        $socialAuthModule = Module::instance();
        $checkOnlyFiscalCode = $socialAuthModule->checkOnlyFiscalCode;

        // Controlli su utente guest - se possibile utilizzare is platformGuest
        $isGuestUser = $this->isGuestUser();

        //Find by other attributes
        $usersByCF = [];
        $countUsersByCF = 0;

        if ($userDatas['codiceFiscale']) {
            $usersByCF = UserProfile::find()->andWhere(['codice_fiscale' => $userDatas['codiceFiscale']])->all();
            $countUsersByCF = count($usersByCF);
        }

        /** @var UserProfile|null $existsByFC */
        $existsByFC = null;

        // Se l'origine dei dati è senza codice fiscale allora non essite nessun aggancio con con codice fiscale da valutare!
        if (in_array(reset($userDatas['rawData']['saml-attribute-originedatiutente']), $this->accessMethodsWithoutCF)) {
            $existsByFC = false;
        } else {
            /** @var UserProfile|null $existsByFC */
            $existsByFC = (($countUsersByCF == 1) ? reset($usersByCF) : null);
        }

        $existsByEmail = null;
        if (!$checkOnlyFiscalCode) {
            $existsByEmail = User::findOne(['email' => $userDatas['emailAddress']]);
        }

        //Get timeout for app login
        $loginTimeout = \Yii::$app->params['loginTimeout'] ?: 3600;

        if ($socialAuthModule->enableSpidMultiUsersSameCF && ($countUsersByCF > 1) && \open20\amos\core\utilities\CurrentUser::isPlatformGuest()) {
            $post = \Yii::$app->request->post();
            if ($post && isset($post['user_by_fiscal_code'])) {
                $usersByCFUserIds = SocialAuthUtility::makeUsersByCFUserIds($usersByCF);
                if (in_array($post['user_by_fiscal_code'], $usersByCFUserIds)) {
                    $user = User::findOne($post['user_by_fiscal_code']);
                    if (!is_null($user)) {
                        //Store IDM user
                        $this->createIdmUser($userDatas, $user->id);

                        $signIn = \Yii::$app->user->login($user, $loginTimeout);

                        if ($signIn === true) {
                            SocialAuthUtility::updateFiscalCode(\Yii::$app->user->id, $userDatas['codiceFiscale']);

                            return ['status' => 'fc'];
                            //return $this->redirect(['/', 'done' => 'fc']);
                        } else {
                            \Yii::$app->getSession()->addFlash('danger', Module::t('amossocialauth', 'Login Failed'));
                        }
                    } else {
                        \Yii::$app->getSession()->addFlash('danger', Module::t('amossocialauth', 'User Not Found, Please try with Other User'));
                    }
                } else {
                    \Yii::$app->getSession()->addFlash('danger', Module::t('amossocialauth', 'User Not Corresponding'));
                }
            }
            // Form to select identity by fiscal code and log-in
            return $this->render('ask-which-user', [
                'userDatas' => $userDatas,
                'usersByCF' => $usersByCF
            ]);
        } elseif ($relation && $relation->id && $isGuestUser) {
            if ($this->isUserDisabled($relation->user_id)) {
                return ['status' => 'disabled', 'user_id' => $relation->user_id];
            }
            
            //Store IDM user
            $this->createIdmUser($userDatas, $relation->user_id);

            //Se l'utente è già collegato logga in automatico
            $signIn = \Yii::$app->user->login($relation->user, $loginTimeout);

            //Remove session data
            \Yii::$app->session->remove('IDM');

            return ['status' => 'rl'];
            //return $this->redirect(['/', 'done' => 'rl']);
        } elseif ($existsByFC && $existsByFC->id && $isGuestUser) {
            if ($this->isUserDisabled($existsByFC->user_id)) {
                return ['status' => 'disabled', 'user_id' => $existsByFC->user_id];
            }

            //Store IDM user
            $this->createIdmUser($userDatas, $existsByFC->user_id);

            $signIn = \Yii::$app->user->login($existsByFC->user, $loginTimeout);

            return ['status' => 'fc'];
            //return $this->redirect(['/', 'done' => 'fc']);
        } elseif (($existsByFC && $existsByFC->id && $existsByFC->user_id == \Yii::$app->user->id && (empty($relation))) && !$isGuestUser) {
            if (\Yii::$app->session->get('connectSpidToProfile')) {
                $this->createIdmUser($userDatas);
                \Yii::$app->session->remove('connectSpidToProfile');
                return ['status' => 'override'];
            }
            //User logged and idm exists, go to home, case not allowed
            //return $this->redirect(['/', 'error' => 'overload']);
        } elseif (($relation && $relation->id) && !$isGuestUser) {
                \Yii::$app->session->addFlash('warning', Module::t('amossocialauth','La tua identità digitale è già associata ad un altro account'));
                \Yii::$app->session->remove('IDM');
                
            //User logged and idm exists, go to home, case not allowed
            //return $this->redirect(['/', 'error' => 'overload']);
        } elseif (!$checkOnlyFiscalCode && $existsByEmail && $existsByEmail->id && $isGuestUser && !$confirmLink) {
            // AUTOMATIC LOGIN & AUTOMATIC REGISTRATION
            if ($socialAuthModule->shibbolethAutoLogin || !$render) {

                if ($socialAuthModule->disableAssociationByEmail) {
                    \Yii::$app->getSession()->addFlash('danger', Module::t('amossocialauth', 'User already registered in the system'));
                    return ['status' => 'disabled-autologin'];
                }

                return ['status' => 'autologin'];
                //return $this->redirect(['/socialauth/shibboleth/endpoint', 'confirm' => true]);
            }

            //Form to confirm identity and log-in
            return $this->render('log-in', [
                'userDatas' => $userDatas,
                'userProfile' => $existsByEmail->profile,
                'authType' => $this->authType,
            ]);
        } elseif (!$checkOnlyFiscalCode && $existsByEmail && $existsByEmail->id && $isGuestUser && $confirmLink) {
            if ($socialAuthModule->disableAssociationByEmail) {
                \Yii::$app->getSession()->addFlash('danger', Module::t('amossocialauth', 'User already registered in the system'));
                return ['status' => 'disabled-autologin'];
            }

            if ($this->isUserDisabled($existsByEmail->id)) {
                return ['status' => 'disabled', 'user_id' => $existsByEmail->id];
            }

            //Store IDM user
            $this->createIdmUser($userDatas, $existsByEmail->id);

            //Login
            $signIn = \Yii::$app->user->login($existsByEmail, $loginTimeout);

            return ['status' => 'conf'];
            //return $this->redirect(['/', 'done' => 'conf']);
        } elseif ($isGuestUser && $render) {
            // AUTOMATIC LOGIN & AUTOMATIC REGISTRATION
            if ($socialAuthModule->shibbolethAutoRegistration || !$render) {
                return ['status' => 'autoregistration'];
            }

            //Form to confirm identity and log-in
            return $this->render('ask-signup', [
                'userDatas' => $userDatas,
                'authType' => $this->authType,
            ]);
        } elseif (!$isGuestUser) {
            //Store IDM user
            $this->createIdmUser($userDatas);
            \Yii::$app->session->remove('connectSpidToProfile');

            return ['status' => 'override'];
            //return $this->redirect(['/', 'done' => 'override']);
        }

        if ($render) {
            return ['status' => 'ND'];
            //return $this->redirect(['/', 'done' => 'ND']);
        }
    }

    /**
     * @return array|mixed
     */
    public function storeDataInSession()
    {
        //Get Headers to ckeck the reverse proxy header datas
        $headers = \Yii::$app->request->getHeaders();

        //Get Session IDM datas (copy of headers)
        $sessionIDM = \Yii::$app->session->get('IDM');

        //Check what type
        if ($headers->get('serialNumber')) {
            $type = 'header_idm';
            $dataFetch = $headers;
        } else if ($headers->get('saml-attribute-codicefiscale') || $headers->get('Shib-Metadata-codicefiscale')) {
            $type = 'header_spid';
            $dataFetch = $headers;
        } else if ($sessionIDM && $sessionIDM['matricola']) {
            $type = 'idm';
            $dataFetch = $headers;
        } else if ($sessionIDM && $sessionIDM['saml-attribute-codicefiscale']) {
            $type = 'spid';
            $dataFetch = $sessionIDM;
        }

        if (!$sessionIDM || !$sessionIDM['matricola']) {
            $matricola = null;
            $nome = null;
            $cognome = null;
            $emailAddress = null;
            $codiceFiscale = null;
            $rawData = null;

            //Based on type i pick the user identiffier
            switch ($type) {
                case 'idm':
                    {
                        $matricola = $dataFetch['serialNumber'];
                        $nome = $dataFetch['name'];
                        $cognome = $dataFetch['familyName'];
                        $emailAddress = $dataFetch['email'];
                        $codiceFiscale = $dataFetch['codiceFiscale'];
                        $rawData = $dataFetch;
                    }
                    break;
                case 'spid':
                    {
                        $matricola = $dataFetch['saml-attribute-codicefiscale'] ?: $dataFetch['Shib-Metadata-codicefiscale'];
                        $nome = $dataFetch['saml-attribute-nome'] ?: $dataFetch['Shib-Metadata-nome'];
                        $cognome = $dataFetch['saml-attribute-cognome'] ?: $dataFetch['Shib-Metadata-cognome'];
                        $emailAddress = $dataFetch['saml-attribute-emailaddress'] ?: $dataFetch['Shib-Metadata-emailaddress'];
                        $codiceFiscale = $dataFetch['saml-attribute-codicefiscale'] ?: $dataFetch['Shib-Metadata-codicefiscale'];
                        $codiceFiscale = $dataFetch;
                    }
                    break;
                case 'header_idm':
                    {
                        $matricola = $dataFetch->get('serialNumber');
                        $nome = $dataFetch->get('name');
                        $cognome = $dataFetch->get('familyName');
                        $emailAddress = $dataFetch->get('email');
                        $codiceFiscale = $dataFetch->get('fiscalCode');
                        $rawData = $dataFetch->toArray();
                    }
                    break;
                case 'header_spid':
                    {
                        $matricola = $dataFetch->get('saml-attribute-codicefiscale') ?: $dataFetch->get('Shib-Metadata-codicefiscale');
                        $nome = $dataFetch->get('saml-attribute-nome') ?: $dataFetch->get('Shib-Metadata-nome');
                        $cognome = $dataFetch->get('saml-attribute-cognome') ?: $dataFetch->get('Shib-Metadata-cognome');
                        $emailAddress = $dataFetch->get('saml-attribute-emailaddress') ?: $dataFetch->get('Shib-Metadata-emailaddress');
                        $codiceFiscale = $dataFetch->get('saml-attribute-codicefiscale') ?: $dataFetch->get('Shib-Metadata-codicefiscale');
                        $rawData = $dataFetch->toArray();
                    }
            }

            /** Override di matricola nel caso di metodo di accesso senza codice fiscale */
            if (in_array($dataFetch->get('saml-attribute-originedatiutente'), $this->accessMethodsWithoutCF)) {
                // la matricola va presa opportunamente in base alla tipologia di accesso
                switch ($dataFetch->get('saml-attribute-originedatiutente')) {
                    case 'UTENTE':
                        $matricola = $dataFetch->get('saml-attribute-nomeutente') ?: $dataFetch->get('Shib-Metadata-nomeutente');
                        break;

                    case 'EIDAS':
                        $matricola = $dataFetch->get('saml-attribute-identificativoutente') ?: $dataFetch->get('Shib-Metadata-identificativoutente');
                        break;

                    // di default se esiste prendiamo identificativoutente...
                    default:
                        $matricola = $dataFetch->get('saml-attribute-identificativoutente') ?: $dataFetch->get('Shib-Metadata-identificativoutente');
                }
            }
    
            if (strpos($codiceFiscale, 'TINIT-') !== false) {
                $spliCF = explode('-', $codiceFiscale);
                $codiceFiscale = end($spliCF);
            }

            //Data to store in session in case header is not filled
            $sessionIDM = [
                'matricola' => $matricola,
                'nome' => $nome,
                'cognome' => $cognome,
                'emailAddress' => $emailAddress,
                'codiceFiscale' => $codiceFiscale,
                'rawData' => $rawData
            ];

            //Store to session
            \Yii::$app->session->set('IDM', $sessionIDM);
        }

        return $sessionIDM;
    }

    /**
     * @param $userDatas
     * @return bool
     */
    public function createIdmUser($userDatas, $user_id = null)
    {
        return SocialAuthUtility::createIdmUser($userDatas, $user_id);
    }

    /**
     * @param $user
     * @return bool
     */
    public function isUserDisabled($user_id)
    {
        $user = User::findOne($user_id);
        if ($user) {
            if ($user->status == User::STATUS_DELETED || !$user->userProfile->attivo) {
                \Yii::$app->session->remove('IDM');
                \Yii::$app->getSession()->addFlash('danger', Module::t('amossocialauth', 'User is disabled'));

                return true;
            }
        }
        return false;
    }

    public function actionSetModuleInstance()
    {
        $moduleId = $this->module->id;

        \Yii::$app->session->set('socialAuthInstance', $moduleId);

        return $this->goHome();
    }


    /**
     * @param null $urlRedirect
     * @return null|\yii\web\Response
     */
    public function actionRemoveSpid($urlRedirect = null)
    {
        SocialAuthUtility::disconnectIdm(\Yii::$app->user->id);
        if ($urlRedirect) {
            return $this->redirect($urlRedirect);
        }
        return $this->goHome();

    }

    public static  function backgroundLogin() {
        $ref = Yii::$app->request->referrer;
        $socialModule = Module::getInstance();

        if(!$socialModule->shibbolethConfig['backgroundLogin']) {
            return false;
        }

        if(!empty($socialModule->shibbolethConfig['backgroundLoginDomains']) && !in_array($ref, $socialModule->shibbolethConfig['backgroundLoginDomains'])) {
            return false;
        }

        echo Html::tag('iframe', '', [
            'src' => '/socialauth/shibboleth/autologin',
            'style' => 'display:none;'
        ]);

        return true;
    }
}
