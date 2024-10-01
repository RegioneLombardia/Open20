<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\socialauth
 * @category   CategoryName
 */

namespace open20\amos\socialauth;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\module\AmosModule;
use open20\amos\socialauth\controllers\ShibbolethController;
use open20\amos\socialauth\models\SocialAuthServices;
use open20\amos\socialauth\models\SocialIdmUser;
use open20\amos\socialauth\utility\SocialAuthUtility;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package open20\amos\socialauth
 */
class Module extends AmosModule implements BootstrapInterface {

    public static $CONFIG_FOLDER = 'config';

    /**
     * @var array providers configuration
     */
    public $providers = [];

    /**
     * @var $enableLogin bool Is Login With Social Enabled?
     */
    public $enableLogin;

    /**
     * @var $enableSpid bool Is Login With Spid Enabled?
     */
    public $enableSpid = false;

    /**
     * If set to true it enable Agid Login
     * @var type
     */
    public $enableAgidLogin = false;

    /**
     * If set to true it enable PUA Login
     * @var type
     */
    public $enablePuaLogin = false;

    /**
     * If set to true it enable PUA Login
     * @var type
     */
    public $enablePuaRegister = false;

    /**
     * If set to true it enable PUA Login
     * @var type
     */
    public $enableCredentialRequest = false;

    /**
     * If set to true it enable PUA Login with controll token
     * @var type
     */
    public $enableTokenControll = false;

    /**
     * Only client_id and client_secret are required.
     * E.g.:
     * ```php
     * 'agidLoginConfiguration' => [
     *        'clientId'       => 'aksdfjlskflkj3230fjlskdf!1djfslsfdfd',
     *        'clientSecret'   => 'jl49sds39fsdfsdlsdjr-asdfj0223dssd32',
     *        'issuerUrl' => 'https://sso-coll.dfp.gov.it',
     *        'name' => 'Pua',
     *        'title' => 'Pua Login',      
     *        'authUrl' => 'https://sso-coll.dfp.gov.it/connect/authorize',
     *        'apiBaseUrl' => 'https://sso-coll.dfp.gov.it',
     *        'scope' => 'openid profile',
     *        'tokenUrl' => 'https://sso-coll.dfp.gov.it/connect/token'
     * ],
     * ```
     * @var array $agidLoginConfiguration
     */
    public $puaConfiguration = [];

    /**
     * Only client_id and client_secret are required.
     * E.g.:
     * ```php
     * 'puaConfiguration' => [
     *        'clientId'       => 'aksdfjlskflkj3230fjlskdf!1djfslsfdfd',
     *        'clientSecret'   => 'jl49sds39fsdfsdlsdjr-asdfj0223dssd32',
     *        'issuerUrl'       => 'https://login.agid.gov.it',
     *        'name'            => 'Agid RTD Devel',
     *        'title'           => 'Agid Login',
     *        'authUrl'         => 'https://login.agid.gov.it/auth',
     *        'apiBaseUrl'      => 'https://login.agid.gov.it',
     *        'scope'           => 'openid email profile',
     *        'tokenUrl'        => 'http://login.agid.gov.it/token',
     * ],
     * ```
     * @var array $puaConfiguration
     */
    public $agidLoginConfiguration = [];

    /**
     *
     * @var bool $agidLoginUseFrontendUrl
     */
    public $agidLoginUseFrontendUrl = true;

    /**
     *
     * @var bool $agidLoginUseFrontendUrl
     */
    public $puaUseFrontendUrl = true;

    /**
     *
     * @var array $shibbolethConfig
     */
    public $shibbolethConfig = [
        'buttonLabel' => '#fullsize_login_spid_text',
        'buttonDescription' => '#fullsize_login_spid_text_right',
        'backgroundLogin' => false,
        'backgroundLoginDomains' => [],
        'updateExtraProfileFields' => false
    ];

    /**
     * @var $enableLogin bool Is Social Account Link Enabled?
     */
    public $enableLink;

    /**
     * @var $enableLogin bool Is Social registration Enabled?
     */
    public $enableRegister;

    /**
     * @var bool Decide if the overload security is enabled (false) or user can be oberloaded by social (true)
     */
    public $userOverload = false;

    /**
     * @var bool $enableServices - array with enabled service names
     */
    public $enableServices = ['calendar', 'contacts'];

    /**
     * @inheritdoc
     */
    public $name = 'socialauth';

    /**
     * @var string $moduleName
     */
    private static $moduleName = 'socialauth';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @var string|null $viewLayout
     */
    public $viewLayout = '@vendor/open20/design/src/views/layouts/bi-main-layout';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\socialauth\controllers';
    public $timeout = 180;

    /**
     * @var bool $enableSpidMultiUsersSameCF If true the model validation doesn't check the unique of che fiscal code.
     */
    public $enableSpidMultiUsersSameCF = false;

    /**
     * @var bool $shibbolethAutoLogin if true on shibboleth controller make automatic login
     */
    public $shibbolethAutoLogin = false;

    /**
     * @var bool $shibbolethAutoRegistration if true on shibboleth controller make automatic registration
     */
    public $shibbolethAutoRegistration = false;

    /**
     * @var bool
     */
    public $disableAssociationByEmail = false;

    /**
     * @var bool $checkOnlyFiscalCode
     */
    public $checkOnlyFiscalCode = true;

    /**
     * @var bool $enableReconciliation
     */
    public $enableReconciliation = false;
    
    /**
     * @var bool $socialAccountAutoRegistration
     */
    public $socialAccountAutoRegistration = true;
   
    /**
     * If set to true it uses basic authentication
     * @var type
     */
    public $useBasicAuthAgidLogin = true;

    /**
     * If set to true it uses basic authentication
     * @var type
     */
    public $useBasicAuthPua = false;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init(); // TODO: Change the autogenerated stub
        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');

        $this->agidLoginConfiguration = \yii\helpers\ArrayHelper::merge($this->getDefaultAgidConfig(), $this->agidLoginConfiguration);
        $this->puaConfiguration = \yii\helpers\ArrayHelper::merge($this->getDefaultPuaConfig(), $this->puaConfiguration);

        if (!(\Yii::$app instanceof yii\console\Application) && ($this->enableAgidLogin || $this->enablePuaLogin)) {
            $components = $this->getAuthClientCollection();
            \Yii::$app->setComponents($components);
        }
        //Configuration
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, $this));
    }

    protected function getDefaultAgidConfig() {
        return [
            'name' => 'Agid',
            'title' => 'Agid Login',
            'issuerUrl' => 'https://login.agid.gov.it',
            'authUrl' => 'https://login.agid.gov.it/auth',
            'apiBaseUrl' => 'https://login.agid.gov.it',
            'scope' => 'openid profile',
            'tokenUrl' => 'https://login.agid.gov.it/token'
        ];
    }

    protected function getDefaultPuaConfig() {
        return [
            'name' => 'Pua',
            'title' => 'Pua Login',
            'issuerUrl' => 'https://sso-coll.dfp.gov.it',
            'authUrl' => 'https://sso-coll.dfp.gov.it/connect/authorize',
            'apiBaseUrl' => 'https://sso-coll.dfp.gov.it',
            'scope' => 'openid profile',
            'tokenUrl' => 'https://sso-coll.dfp.gov.it/connect/token'
        ];
    }

    /**
     * 
     * @return array
     */
    public function getAuthClientCollection() {
        $collection = [
            'authClientCollection' => [
                'class' => 'yii\authclient\Collection',
                'clients' => [
                    'agid' => [
                        'class' => 'yii\authclient\OpenIdConnect',
                        'issuerUrl' => $this->agidLoginConfiguration['issuerUrl'],
                        'name' => $this->agidLoginConfiguration['name'],
                        'title' => 'Agid Login',
                        'authUrl' => $this->agidLoginConfiguration['authUrl'],
                        'apiBaseUrl' => $this->agidLoginConfiguration['apiBaseUrl'],
                        'scope' => $this->agidLoginConfiguration['scope'],
                        'tokenUrl' => $this->agidLoginConfiguration['tokenUrl'],
                    ],
                    'pua' => [
                        'class' => 'yii\authclient\OpenIdConnect',
                        'issuerUrl' => $this->puaConfiguration['issuerUrl'],
                        'name' => $this->puaConfiguration['name'],
                        'title' => 'PUA Login',
                        'authUrl' => $this->puaConfiguration['authUrl'],
                        'apiBaseUrl' => $this->puaConfiguration['apiBaseUrl'],
                        'scope' => $this->puaConfiguration['scope'],
                        'tokenUrl' => $this->puaConfiguration['tokenUrl'],
                    ],
                ],
            ]
        ];
        return $collection;
    }

    /**
     * @inheritdoc
     */
    public static function getInstance() {
        if (Yii::$app->has('session') && Yii::$app->session->has('socialAuthInstance')) {
            return Yii::$app->getModule(Yii::$app->session->get('socialAuthInstance'));
        }

        return parent::getInstance();
    }

    /**
     * @return array
     */
    public function getProviders() {
        return $this->providers;
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName() {
        return static::$moduleName;
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons() {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics() {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels() {
        return [
        ];
    }

    /**
     * @param string $provider
     * @param null|SocialAuthServices $service
     * @return \Google_Client|null
     */
    public function getClient($provider = 'google', $service = null) {
        $providers = $this->providers;
        $client = null;
        if ($provider == 'google') {
            if (!empty($providers) && array_key_exists('Google', $providers)) {
                $key = $providers['Google']['keys']['secret'];
                $clientId = $providers['Google']['keys']['id'];
                $client = new \Google_Client();
                $client->setClientId($clientId);
                $client->setClientSecret($key);
                if (!is_null($service)) {
                    $accessToken['access_token'] = $service->access_token;
                    $accessToken['token_type'] = $service->token_type;
                    $accessToken['expires_in'] = $service->expires_in;
                    $accessToken['created'] = $service->token_created;
                    $client->setAccessToken($accessToken);
                    // Refresh the token if it's expired.
                    if ($client->isAccessTokenExpired()) {
                        $accessToken = $client->fetchAccessTokenWithRefreshToken($service->refresh_token);
                        $service->access_token = $accessToken['access_token'];
                        $service->token_type = $accessToken['token_type'];
                        $service->expires_in = $accessToken['expires_in'];
                        $service->token_created = $accessToken['created'];
                        if (!$service->save()) {
                            \Yii::$app->session->addFlash('danger',
                                    Module::t('amossocialauth',
                                            'An error occurred while synchronizing {provider} {serviceName}',
                                            ['provider' => $provider, 'serviceName' => $service->service]));
                        }
                    }
                }
            }
        }
        return $client;
    }

    /**
     * @param SocialAuthServices $service
     * @return string $message
     */
    public function synchronizeGoogleService($service) {
        $message = '';
        $client = $this->getClient('google', $service);
        if ($service->service == 'calendar') {
            $serviceGoogle = new \Google_Service_Calendar($client);
            $calendarId = $service->service_id;
            if (empty($calendarId)) {
                $calendar = new \Google_Service_Calendar_Calendar();
                $calendar->setSummary(\Yii::$app->name);
                $calendar = $serviceGoogle->calendars->insert($calendar);
                $calendarId = $calendar->getId();

                $service->service_id = $calendarId;
                $service->save(false);
                $message = Module::t('amossocialauth',
                                'Calendar \'{appName}\' has been added successfully to your Google Account.',
                                ['appName' => \Yii::$app->name]);
            } else {
                $message .= Module::t('amossocialauth', 'Calendar \'{appName}\' synchronization',
                                ['appName' => \Yii::$app->name]);
            }

            $eventsModule = Yii::$app->getModule('events');
            if (!is_null($eventsModule)) {
                return $eventsModule->synchronizeEvents($serviceGoogle, $calendarId, $message);
            }
        } elseif ($service->service == 'contacts') {
            $serviceGoogle = new \Google_Service_PeopleService($client);
            $message = AmosAdmin::synchronizeGoogleContacts($serviceGoogle);
        }
        return $message;
    }

    /**
     * @param SocialAuthServices $service
     * @return string
     */
    public function removeGoogleService($service) {
        $message = '';
        $client = $this->getClient();
        $accessToken['access_token'] = $service->access_token;
        $accessToken['token_type'] = $service->token_type;
        $accessToken['expires_in'] = $service->expires_in;
        $accessToken['created'] = $service->token_created;
        $client->setAccessToken($accessToken);
        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $accessToken = $client->fetchAccessTokenWithRefreshToken($service->refresh_token);
            $service->access_token = $accessToken['access_token'];
            $service->token_type = $accessToken['token_type'];
            $service->expires_in = $accessToken['expires_in'];
            $service->token_created = $accessToken['created'];
            if (!$service->save()) {
                $message = Module::t('amosadmin', 'Errore durante l\'aggiornamento delle impostazioni');
            }
        }
        if ($service->service == 'calendar') {
            $message .= ' calendar : ';
            $serviceGoogle = new \Google_Service_Calendar($client);
            $calendarId = $service->service_id;
            if (!empty($calendarId)) {
                $calendar = $serviceGoogle->calendars->get($calendarId);
                if ($calendar) {
                    $message .= $calendarId . ' ';
                }
            }
            $serviceGoogle->calendars->delete($calendarId);
        } elseif ($service->service == 'contacts') {
            AmosAdmin::removeGoogleContacts();
        }
        return $message;
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app) {
        if (Yii::$app instanceof yii\console\Application) {
            return true;
        }

        //Init a new shibboleth controller to link user
        $shibbolethController = new ShibbolethController('shibboleth', $this);

        Event::on(AmosAdmin::instance()->model('UserProfile'), UserProfile::EVENT_AFTER_INSERT,
                ['open20\amos\socialauth\utility\SocialAuthUtility', 'createIdmUser']);

        //Get Session IDM datas (copy of headers)
        $sessionIDM = \Yii::$app->session->get('IDM');

        //Trigger ad evento
        if ($this->shibbolethConfig['backgroundLogin']) {
            Yii::$app->on('BEFORE_LOGIN_FORM', [ShibbolethController::className(), 'backgroundLogin']);
        }

        //Link to current user with IDM
        if (!\Yii::$app->user->isGuest) {
            if ($sessionIDM && $sessionIDM['codiceFiscale']) {
                return $shibbolethController->tryIdmLink(true, true, false);
            } elseif ($sessionIDM && $sessionIDM['matricola']) {
                return $shibbolethController->tryIdmLink(true, true, false);
            } else if ($sessionIDM && $sessionIDM['saml-attribute-codicefiscale']) {
                return $shibbolethController->tryIdmLink(true, true, false);
            }
        }

        return true;
    }

    /**
     * This method find the association between the platform user and the social idm user (SPID).
     * @param int $userId
     * @return SocialIdmUser|null
     */
    public function findSocialIdmByUserId($userId) {
        return SocialAuthUtility::findSocialIdmByUserId($userId);
    }

}
