<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin
 * @category   CategoryName
 */

namespace open20\amos\admin;

use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\exceptions\AdminException;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile;
use open20\amos\admin\widgets\graphics\WidgetGraphicsUsers;
use open20\amos\admin\widgets\icons\WidgetIconMyProfile;
use open20\amos\admin\widgets\icons\WidgetIconUserProfile;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\user\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class AmosAdmin
 * @package open20\amos\admin
 */
class AmosAdmin extends AmosModule implements SearchModuleInterface
{
    const site_key_param = 'google_recaptcha_site_key';
    const secret_param = 'google_recaptcha_secret';
    //google contacts session keys
    const GOOGLE_CONTACTS = 'contacts';
    const GOOGLE_CONTACTS_PLATFORM = 'contacts_platform';
    const GOOGLE_CONTACTS_NOT_PLATFORM = 'contacts_not_platform';

    public $controllerNamespace = 'open20\amos\admin\controllers';
    public $whiteListRoles = [];
    public $name = 'Utenti';
    public $searchListFields = [];
    public $hardDelete = false;
    public $frontend_auto_login = false;
    public $frontend_autologin_token_group = '';

    /**
     * @var bool $enableRegister - set to true to enable user register to the application and create his own userprofile
     */
    public $enableRegister = false;

    /**
     * @var bool $showLogInRegisterButton - set to true to enable user register button on login form
     */
    public $showLogInRegisterButton = true;

    /**
     * @var bool $hideStandardLoginPageSection If true hide the login page section where the user can insert username and password.
     */
    public $hideStandardLoginPageSection = false;

    /**
     * @var string $textWarningForRegisterDisabled - set the text that will to show if the register is disabled
     */
    public $textWarningForRegisterDisabled;

    /**
     * @var bool $enableUserContacts
     * enable connection to users, send private messages, and see 'contacts' in section 'NETWORK' of the user profile
     */
    public $enableUserContacts = true;

    /**
     * @var bool $enableSendMessage If this is true and $enableUserContacts is false all users see the "send message" button on view icon.
     */
    public $enableSendMessage = false;

    /**
     * @var bool
     */
    public $hideContactsInView = false;

    /**
     * @var bool
     */
    public $accordionNetworkOpenOnDefault = false;

    /**
     * @var bool $cached - enable or not admin query caching
     */
    public $cached = false;

    /**
     * @var int $cacheDuration
     * seconds of query caching duration if $cache = true - default is 1 day
     */
    public $cacheDuration = 84600;

    /**
     * @var bool $bypassWorkflow If true the plugin bypass the user profile workflow and show nothing of it.
     */
    public $bypassWorkflow = false;

    /**
     * This is the html used to render the subject of the e-mail. In the view is available the variable $profile
     * that is an instance of 'open20\amos\admin\models\UserProfile'
     * @var string
     */
    public $htmlMailSubject = '@vendor/open20/amos-admin/src/mail/user/credenziali-subject';

    /**
     * This is the html used to render the message of the e-mail. In the view is available the variable $profile
     * that is an instance of 'open20\amos\admin\models\UserProfile'
     * @var string
     */
    public $htmlMailContent = '@vendor/open20/amos-admin/src/mail/user/credenziali-html';

    /**
     * This is the text used to render the message of the e-mail. In the view is available the variable $profile
     * that is an instance of 'open20\amos\admin\models\UserProfile'
     * @var string
     */
    public $textMailContent = '@vendor/open20/amos-admin/src/mail/user/credenziali-text';
    public $htmlMailForgotPasswordSubjectView = '@vendor/open20/amos-admin/src/mail/user/forgotpassword-subject';
    public $htmlMailForgotPasswordView = '@vendor/open20/amos-admin/src/mail/user/forgotpassword-html';

    /**
     * This is the html content used to render the message of the e-mail send to user that had invited someone
     * @var string
     */
    public $htmlMailNotifyAcceptedRegistrationRequestContent = '@vendor/open20/amos-admin/src/mail/user/notify-accepted-registration-request-html';

    /**
     * This is the html subject used to render the message of the e-mail send to user that had invited someone
     * @var string
     */
    public $htmltMailNotifyAcceptedRegistrationRequestSubject = '@vendor/open20/amos-admin/src/mail/user/notify-accepted-registration-request-subject';

    /**
     * @var array $fieldsConfigurations This array contains all configurations for boxes and fields.
     */
    public $fieldsConfigurations = [
        'boxes' => [
            'box_informazioni_base' => ['form' => true, 'view' => true]
        ],
        'fields' => [
            'nome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
            'cognome' => ['form' => true, 'view' => true, 'referToBox' => 'box_informazioni_base'],
            'userProfileImage' => ['form' => true, 'view' => true, 'referToBox' => 'box_foto']
        ]
    ];

    /**
     * @var array $profileRequiredFields - mandatory fields in user profile form
     */
    public $profileRequiredFields = [
        'nome',
        'cognome',
        'status',
        'presentazione_breve'
    ];

    /**
     * @var ConfigurationManager $confManager
     */
    public $confManager = null;

    /**
     * At user creation, it is possible to customize the Rbac role to assign to a new user, default is BASIC_USER role.
     *
     * @var string $defaultUserRole
     */
    public $defaultUserRole = 'BASIC_USER';

    /**
     * This is the module name (you used as array key in modules configuration of your platform) referring to a module
     * extending open20\amos\core\interfaces\OrganizationsModuleInterface
     * It is used to give the possibility to customize the entity type used to set user profile prevalent partnership, for example.
     *
     * @var string $organizationModuleName
     */
    private $organizationModuleName = "organizzazioni";

    /**
     * @var bool $allowLoginWithEmailOrUsername
     */
    public $allowLoginWithEmailOrUsername = true;

    /**
     * @var bool $userCanSelectUsername
     */
    public $userCanSelectUsername = false;

    /**
     * @var bool $disableUpdateChangeStatus
     */
    public $disableUpdateChangeStatus = false;

    /**
     * @var bool $enableWorkflowChangeStatusMails
     */
    public $enableWorkflowChangeStatusMails = true;

    /**
     * @var string $whiteListProfileImageExts List of the allowed extensions for the upload of profile image.
     */
    public $whiteListProfileImageExts = 'jpeg, jpg, png, gif';

    /**
     * @var string $moduleName
     */
    private static $moduleName = 'admin';

    /**
     * @var array $defaultListViews This set the default order for the views in lists
     */
    public $defaultListViews = ['icon', 'grid', 'list'];

    /**
     * @var bool $forceDefaultViewType
     */
    public $forceDefaultViewType = false;

    /**
     *  At the creation of the user, set the user (Tutor) as contact of the created user
     * @var null|int $user_id
     */
    public $associateTutor = null;

    /**
     *  At the creation of the user, send a private message from the tutor
     * @var null|boolean
     */
    public $defaultPrivateMessage = false;

    /**
     * Set the backend action (url) to go to if the private message above is sent
     * @var null|string
     */
    public $helpLinkAction = null;

    /**
     * @var bool $roleAndAreaOnOrganizations If true, hide roles and areas standard and enable it on the single network organization row
     */
    public $roleAndAreaOnOrganizations = false;

    /**
     * @var bool $roleAndAreaFromOrganizationsWithTypeCat If true, uses "type_cat" field in the roles and areas queries
     */
    public $roleAndAreaFromOrganizationsWithTypeCat = false;

    /**
     * @var bool $sendUserAssignmentsReportOnDelete Send a report of user assignments via email if true
     */
    public $sendUserAssignmentsReportOnDelete = false;

    /**
     * @var bool $showFacilitatorForModuleSelect Display a select multiple field for assigning specific module facilitator permissions in the user profile form
     */
    public $showFacilitatorForModuleSelect = false;

    /**
     * @var bool $dontCheckOneTagPresent If true the model validation doesn't check if there's at least one tag present for non ADMIN users.
     */
    public $dontCheckOneTagPresent = false;

    /**
     * @var bool $enableMultiUsersSameCF If true the model validation doesn't check the unique of che fiscal code.
     */
    public $enableMultiUsersSameCF = false;

    /**
     * @var bool $bypassRequiredForAdmin If true the required fields for logged user admin is only name and surname when update another user.
     */
    public $bypassRequiredForAdmin = true;

    /**
     * @var bool
     */
    public $enableExternalFacilitator = false;

    /**
     * @var bool
     */
    public $disableFirstAccesWizard = false;

    /**
     *
     * @var array
     */
    public $excludeWizardByMails = ['demo@example.com'];

    /**
     * @var bool
     */
    public $disableSendValidationRequestAuto = false;

    /**
     * @var bool
     */
    public $sendValidationRejectionEmail = false;

    /**
     * @inheritdoc
     */
    public $db_fields_translation = [
        [
            'namespace' => 'open20\amos\admin\models\UserProfileArea',
            'attributes' => ['name'],
            'category' => 'amosadmin'
        ],
        [
            'namespace' => 'open20\amos\admin\models\UserProfileRole',
            'attributes' => ['name'],
            'category' => 'amosadmin'
        ],
    ];

    /**
     * @var bool
     */
    public $precompileUsernameOnFirstAccess = false;

    /**
     * @var bool $enableUserContactsWidget
     */
    public $enableUserContactsWidget = true;
    public $defaultProfileImagePath = "@webroot/img/defaultProfilo.png";

    /**
     * @var bool $enableInviteUserToEvent If true enable a link on single user useful to invite a user to a published event with an event community.
     */
    public $enableInviteUserToEvent = false;

    /**
     * Is set true the validate basic user can create contents only in his/her own Communities
     *
     * @var bool $createContentInMyOwnCommunityOnly
     */
    public $createContentInMyOwnCommunityOnly = false;

    /* Array che verifica se una action appartiene alla blacklist oppure no */
    /* ovvero se un ruolo è autorizzato o meno a lanciare una action. */
    public $actionBlacklistManageInvite = [];

    /**
     * @var bool $bypassConfirmForFacilitator
     * If true bypass confirm for facilitator, autoconfirm without request.
     *
     */
    public $bypassConfirmForFacilitator = false;

    /**
     * Accoppiamento stretto con altra entità, permette di filtrare gli utenti in join con altra tabella
     * @var type $tightCoupling
     */
    public $tightCoupling = false;

    /**
     * Permette di configurare il model e il campo da verificare per filtrare in base all'appartenenza dell'utente
     * loggato al medesimo gruppo. Il campo implicito di verifica è lo user_id sia sullo UserProfile sia sulla nuova entità
     * Esempio:
     * ```php
     * ['\open20\amos\events\models\EventGroupReferentMm' => 'event_group_referent_id']
     * ```
     * @var array $tightCouplingModel
     */
    public $tightCouplingModel;

    /**
     * Permette di configurare la classe come chiave e il metodo come valore dell'array per poter effettuare
     * la query di recupero dei gruppi
     * Esempio:
     * ```php
     * ['\open20\amos\events\models\EventGroupReferent' => 'getTightCouplingGroups']
     * ```
     * @var array $tightCouplingModel
     */
    public $tightCouplingMethod;

    /**
     * Permette di definire il nome del campo da utilizzare come campo visualizzato nella select del gruppo nel profilo
     * @var string
     */
    public $tightCouplingMethodField = 'denominazione';

    /**
     * Ruolo assegnato in caso di associazione al gruppo
     * @var string $tightCouplingAssignmentRole
     */
    public $tightCouplingAssignmentRole;

    /**
     * Campo se settato a 1 da escludere nelle query di qualsiasi tipo nel profilo
     * @var type
     */
    public $tightCouplingExcludeField = 'exclude_from_query';

    /**
     * Ruolo per il quale non verranno filtrati i risultati se l'accoppiamento stretto è attivo e configurato
     * @var type $tightCouplingRoleAdmin
     */
    public $tightCouplingRoleAdmin = 'ADMIN';

    /**
     * @var Array
     */
    public $enableAttributeChangeLog = [];

    /**
     * @var bool
     */
    public $enableValidationEmail = false;

    /**
     * @var bool
     */
    public $enableDlSemplification = false;

    /**
     * @return string
     */
    public function getOrganizationModuleName()
    {
        return $this->organizationModuleName;
    }

    /**
     * @param string $organizationModuleName
     */
    public function setOrganizationModuleName($organizationModuleName)
    {
        $this->organizationModuleName = $organizationModuleName;
    }

    /**
     * Module name
     * @return string
     */
    public static function getModuleName()
    {
        return static::$moduleName;
    }

    /**
     * @param string $moduleName
     */
    public static function setModuleName($moduleName)
    {
        static::$moduleName = $moduleName;
    }

    public static function getModelSearchClassName()
    {
        //return __NAMESPACE__ . '\models\search\UserProfileSearch';
        $admin = AmosAdmin::instance();
        $userClass = $admin->model('UserProfileSearch');
        return $userClass;
    }

    public static function getModuleIconName()
    {
        return 'users';
    }

    /**
     * Module Initialization
     * @throws AdminException
     */
    public function init()
    {
        parent::init();

        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers',
            __DIR__ . '/controllers/');
        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this,
            require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));

        $this->confManager = new ConfigurationManager([
            'fieldsConfigurations' => $this->fieldsConfigurations
        ]);
        $this->confManager->checkFieldsConfigurationsStructure();

        //dependency injection of reCaptcha
        if (isset($this->reCaptcha)) {
            if (isset(\Yii::$app->params[self::site_key_param])) {
                $this->reCaptcha->siteKey = \Yii::$app->params[self::site_key_param];
            }
            if (isset(\Yii::$app->params[self::secret_param])) {
                $this->reCaptcha->secret = \Yii::$app->params[self::secret_param];
            }
            \Yii::$app->set('reCaptcha', $this->reCaptcha);
        }
        $this->profileRequiredFields = array_unique($this->profileRequiredFields);
    }

    /**
     * Array of widget-namespaces that belong to the module
     * @return array
     */
    public function getWidgetGraphics()
    {
        return [
            WidgetGraphicMyProfile::className(),
            WidgetGraphicsUsers::className()
        ];
    }

    /**
     * Array of widget-namespaces that belong to the module
     * @return array
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconMyProfile::className(),
            WidgetIconUserProfile::className()
        ];
    }

    /**
     * Get roles white-list
     * @return array
     * @deprecated
     */
    public function getWhiteListRules() // TODO change to getWhiteListRoles()
    {
        trigger_error('Deprecated: this function is repleca by getWhiteListRoles',
            E_NOTICE);
        return $this->whiteListRoles;
    }

    /**
     * Return list of white Roles
     * @return []
     */
    public function getWhiteListRoles()
    {
        return $this->whiteListRoles;
    }

    /**
     * Get default models
     * @return array
     */
    protected function getDefaultModels()
    {
        return [
            'UserProfile' => __NAMESPACE__ . '\\' . 'models\UserProfile',
            'UserContact' => __NAMESPACE__ . '\\' . 'models\UserContact',
            'UserProfileStatiCivili' => __NAMESPACE__ . '\\' . 'models\UserProfileStatiCivili',
            'UserProfileTitoliStudio' => __NAMESPACE__ . '\\' . 'models\UserProfileTitoliStudio',
            'User' => 'open20\amos\core\user\User',
            'IstatComuni' => 'open20\amos\comuni\models\IstatComuni',
            'IstatProvince' => 'open20\amos\comuni\models\IstatProvince',
            'IstatRegioni' => 'open20\amos\comuni\models\IstatRegioni',
            'IstatNazioni' => 'open20\amos\comuni\models\IstatNazioni',
            'ForgotPasswordForm' => __NAMESPACE__ . '\\' . 'models\ForgotPasswordForm',
            'LoginForm' => __NAMESPACE__ . '\\' . 'models\LoginForm',
            'ProfileReactivationForm' => __NAMESPACE__ . '\\' . 'models\ProfileReactivationForm',
            'RegisterForm' => __NAMESPACE__ . '\\' . 'models\RegisterForm',
            'CambiaPasswordForm' => __NAMESPACE__ . '\\' . 'models\CambiaPasswordForm',
            'Ruoli' => 'common\models\Ruoli',
            'UserProfileSearch' => __NAMESPACE__ . '\\' . 'models\search\UserProfileSearch',
            'UserContactSearch' => __NAMESPACE__ . '\\' . 'models\search\UserContactSearch',
            'UserProfileTitoliStudioSearch' => __NAMESPACE__ . '\\' . 'models\search\UserProfileTitoliStudioSearch',
        ];
    }

    /**
     * The method create a new account. It creates a new User and new UserProfile only with
     * name, surname and email. The email must be unique in the database! This method returns
     * the user id if all goes well. It returns boolean false in case of errors.
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param \open20\amos\community\models\Community $community
     * @param bool|false $sendCredentials if credential mail must be sent to the newly created user
     * @return array
     */
    public function createNewAccount($name, $surname, $email, $privacy,
                                     $sendCredentials = false,
                                     $community = null,
                                     $urlFirstAccessRedirectUrl = null)
    {
        return UserProfileUtility::createNewAccount($name, $surname, $email,
            $privacy, $sendCredentials, $community,
            $urlFirstAccessRedirectUrl);
    }

    /**
     * @param \Google_Service_PeopleService $serviceGoogle
     * @return string $message
     */
    public static function synchronizeGoogleContacts($serviceGoogle)
    {
        $connections = $serviceGoogle->people_connections->listPeopleConnections(
            'people/me', array('personFields' => 'photos,names,emailAddresses'));
        $items = $connections->getTotalItems();
        $message = AmosAdmin::t('amosadmin', 'Google contacts: {count}',
            ['count' => $items]);
        $session = Yii::$app->session;
        if ($items) {
            $contacts = [];
            $inPlatform = [];
            $notInplatform = [];
            $i = 0;
            /**  @var \Google_Service_PeopleService_Person $connection */
            foreach ($connections as $connection) {
                $contact = [];
                $names = [];
                $photos = [];
                $names['displayName'] = ArrayHelper::getColumn($connection->getNames(),
                    'displayName');
                $names['name'] = ArrayHelper::getColumn($connection->getNames(),
                    'givenName');
                $names['surname'] = ArrayHelper::getColumn($connection->getNames(),
                    'familyName');
                $photos['url'] = ArrayHelper::getColumn($connection->getPhotos(),
                    'url');
                $emails = ArrayHelper::getColumn($connection->getEmailAddresses(),
                    'value');
                $emails = array_unique($emails);
                foreach ($emails as $email) {
                    $contact['email'] = $email;
                    $contact['names'] = $names;
                    $contact['photos'] = $photos;
                    $user = User::findByEmail($email);
                    if (!is_null($user)) {
                        $contact['user_id'] = $user->id;
                        $inPlatform[] = $user->id;
                    } else {
                        $notInplatform[] = $i;
                    }
                    $contacts[$i] = $contact;
                    $i++;

                    $session->set(self::GOOGLE_CONTACTS, $contacts);
                    $session->set(self::GOOGLE_CONTACTS_PLATFORM, $inPlatform);
                    $session->set(self::GOOGLE_CONTACTS_NOT_PLATFORM,
                        $notInplatform);
                }
            }
            $message .= '<br/>' . AmosAdmin::t('amosadmin',
                    'Registered in \'{appName}\': {count}',
                    ['appName' => \Yii::$app->name, 'count' => count($inPlatform)]);
        }
        return $message;
    }

    public static function removeGoogleContacts()
    {
        $session = Yii::$app->session;
        if ($session->has(self::GOOGLE_CONTACTS)) {
            $session->remove(self::GOOGLE_CONTACTS);
        }
        if ($session->has(self::GOOGLE_CONTACTS_PLATFORM)) {
            $session->remove(self::GOOGLE_CONTACTS_PLATFORM);
        }
        if ($session->has(self::GOOGLE_CONTACTS_NOT_PLATFORM)) {
            $session->remove(self::GOOGLE_CONTACTS_NOT_PLATFORM);
        }
    }

    /**
     * @param null|int $id
     * @return null|string
     */
    public static function fetchGoogleContacts($id = null)
    {
        $socialAuth = Yii::$app->getModule('socialauth');
        if (!is_null($socialAuth)) {
            if (is_null($id)) {
                $userId = Yii::$app->user->id;
                $profile = UserProfile::findOne(['user_id' => $userId]);
            } else {
                $profile = UserProfile::findOne($id);
            }
            if (!is_null($profile)) {
                $socialAuthUser = $profile->getSocialAuthUsers()->andWhere(['provider' => 'google'])->one();
                if ($socialAuthUser) {
                    $service = $socialAuthUser->getServices()->andWhere(['service' => 'contacts'])->one();
                    if ($service) {
                        return $socialAuth->synchronizeGoogleService($service);
                    }
                }
            }
        }
        return null;
    }

    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\UserProfile';
    }

    //nuovo metodo controllo su blacklist
    public function checkManageInviteBlackList()
    {
        return in_array(\Yii::$app->controller->action->id,
            $this->actionBlacklistManageInvite);
    }

    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = "";
        $app = \Yii::$app;
        if (!$app->user->isGuest) {
            if (Yii::$app->user->can('GESTIONE_UTENTI')) {
                $menu .= $this->addFrontEndMenu(AmosAdmin::t('amos' . AmosAdmin::getModuleName(),
                    '#menu_front_events'),
                    AmosAdmin::toUrlModule('/user-profile/validated-users'), $dept);
            }
        }
        return $menu;
    }
}