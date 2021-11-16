<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi
 * @category   CategoryName
 */

namespace open20\amos\sondaggi;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\sondaggi\widgets\icons\WidgetIconAmministraSondaggi;
use open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi;
use open20\amos\sondaggi\widgets\icons\WidgetIconPubblicaSondaggi;
use open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi;
use Yii;
use yii\db\Connection;
use yii\helpers\ArrayHelper;
use open20\amos\core\interfaces\BreadcrumbInterface;

/**
 * Class AmosSondaggi
 * @package open20\amos\sondaggi
 */
class AmosSondaggi extends AmosModule implements ModuleInterface, CmsModuleInterface, BreadcrumbInterface
{
    public static $CONFIG_FOLDER = 'config';
    public $controllerNamespace  = 'open20\amos\sondaggi\controllers';
    public $newFileMode          = 0666;
    public $newDirMode           = 0777;

    /**
     * In the case of a private poll for role, it is possible to send the notification to the users who can fill out the survey.
     * @var boolean
     */
    public $enableNotificationEmailByRoles = true;

    /**
     * Enables answer ordering during compilation.
     * @var boolean
     */
    public $enableAnswerOrdering = true;

    /**
     * Enables answer validation during compilation.
     * @var boolean
     */
    public $enableAnswerValidation = true;

    /**
     * Default email for the sender
     * @var string
     */
    public $defaultEmailSender;

    /**
     * It allows to show in the first page of the results the geoChart based on the province of domicile.
     * @var boolean
     */
    public $enableGeoChart = false;

    /**
     * It allows to show in the first page of the results a partecipant report if available.
     * @var boolean
     */
    public $enablePartecipantsReport = false;

    /**
     * It allows to create, delete and order pages with an explicit order.
     * @var boolean
     */
    public $orderPages = false;

    /**
     * Zips XLS recap file (with attachments).
     * @var boolean
     */
    public $xlsAsZip = false;

    /**
     * Removes intro in questions.
     * @var boolean
     */
    public $questionIntro = true;

    /**
     * The fields that will be displayed in the participant's //TO-DO
     * @var array
     */
    public $fieldsByPartecipants = [];

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    public $name   = 'Sondaggi';

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     */
    public $db = 'db';

    /**
     * Hide the Option wheel in the graphic widget
     * @var bool|false $hideWidgetGraphicsActions
     */
    public $hideWidgetGraphicsActions = false;

    /**
     * @var array $viewPathEmailSummary
     */
    public $viewPathEmailSummary = [
        'open20\amos\sondaggi\models\Sondaggi' => '@vendor/open20/amos-sondaggi/src/views/email/notify_summary'
    ];

    /**
     * @var array $viewPathEmailSummaryNetwork
     */
    public $viewPathEmailSummaryNetwork = [
        'open20\amos\sondaggi\models\Sondaggi' => '@vendor/open20/amos-sondaggi/src/views/email/notify_summary_network'
    ];

    /**
     *
     * @var bool $enableDashboard
     */
    public $enableDashboard = false;

    /**
     *
     * @var bool $enableCompilationWorkflow
     */
    public $enableCompilationWorkflow = false;

    /**
     *
     * @var bool $enableSingleCompilation
     */
    public $enableSingleCompilation = false;

    /**
     *
     * @var bool $enableRecompile
     */
    public $enableRecompile = false;

    /**
     *
     * @var bool $enableInvitationList
     */
    public $enableInvitationList = false;

    /**
     * It links poll compilation to an organization and not to a user.
     * @var boolean
     */
    public $compilationToOrganization = false;

    /**
     * Hides Own Interest in poll list.
     * @var boolean
     */
    public $hideOwnInterest = false;

    /**
     * to turn on communications management
     * @var boolean
     */
    public $hasComunications = true;

    /**
     * to turn on invitation managemsent
     * @var boolean
     */
    public $hasInvitation = true;

    /**
     * force the sondaggio to be of type frontend
     * @var boolean
     */
    public $forceOnlyFrontend = false;

    /**
     * enable the field "SONDAGGIO COMPILABILE IN FRONTEND"
     * if forceOnlyFrontend is set to false.
     * @var boolean
     */
    public $enableFrontendCompilation = false;

    /**
     *
     * @var array $compilationWorkflowRules
     */
    public $compilationWorkflowRules = [
        'SondaggiSessioniWorkflow/BOZZA' => ['AMMINISTRAZIONE_SONDAGGI'],
        'SondaggiSessioniWorkflow/RICHIESTA_INVIO' => ['COMPILA_SONDAGGIO'],
        'SondaggiSessioniWorkflow/INVIATO' => ['COMPILA_SONDAGGIO'],
    ];

    /**
     *
     * @var int $numberListTag
     */
    public $numberListTag = 10;

    /**
     *
     * @var bool $forceRecompileFromTheBeginning
     */
    public $forceRecompileFromTheBeginning = false;

    /**
     * @var $disableLinkAll
     */
    public $disableLinkAll = false;

    /**
     * @var array $activatePoolRoles
     */
    public $activatePoolRoles = [];

    /**
     * @var string $frontendControllerLayoutPath
     */
    public $frontendControllerLayoutPath = '@frontend/views/layouts/main';

    /**
     *
     * @var bool $enableCriteriValutazione
     */
    public $enableCriteriValutazione = false;


    /**
     * Enable advanced settings when creating the survey
     * @var bool $enableAdvancedSettings
     */
    public $enableAdvancedSettings = true;

    /**
     *
     * @var bool $statisticDisableLoggedUsers
     */
    public $statisticDisableLoggedUsers = false;

    /**
     * This param disable name, surname and email from extraction
     * If the survey is of type abilita_registrazione = true this param is irrelevant
     * and the three fields will still be displayed
     *
     * @var bool $statisticExtractDisableNameSurnameEmail
     */
    public $statisticExtractDisableNameSurnameEmail = false;

    /**
     * If set to TRUE it enables export before closing
     * @var bool $enableExportBeforeClosing
     */
    public $enableExportBeforeClosing = false;

    /**
     * If set to TRUE it enables to save param extra_field setted in the sondaggi from get
     * @var bool $enableExtraFieldByGet
     */
    public $enableExtraFieldByGet = false;

    /**
     * If set to TRUE it force the language be get param language
     * @var bool  $enableForceLanguageByGet
     */
    public $enableForceLanguageByGet = false;

    /**
     * @var string $sondaggioDataConfirmMessage
     */
    public $sondaggioDataConfirmMessage = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->db = Yii::$app->db;
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'open20\amos\sondaggi\commands';
        }
        \Yii::setAlias('@open20/amos/'.static::getModuleName().'/controllers/', __DIR__.'/controllers/');
        // initialize the module with the configuration loaded from config.php
        $config = require(__DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
        Yii::configure($this, ArrayHelper::merge($config, $this));
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "sondaggi";
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconSondaggi::className(),
            WidgetIconCompilaSondaggi::className(),
            WidgetIconPubblicaSondaggi::className(),
            WidgetIconAmministraSondaggi::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'Sondaggi' => __NAMESPACE__.'\\'.'models\Sondaggi',
            'SondaggiSearch' => __NAMESPACE__.'\\'.'models\search\SondaggiSearch',
        ];
    }

    /**
     *
     * @return string
     */
    public static function getModelClassName()
    {
        return AmosSondaggi::instance()->model('Sondaggi');
    }

    /**
     *
     * @return string
     */
    public static function getModelSearchClassName()
    {
        return AmosSondaggi::instance()->model('SondaggiSearch');
    }

    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = "";
        $app  = \Yii::$app;
        if ((is_null($app->user) || $app->user->id == $app->params['platformConfigurations']['guestUserId'])) {
            //$menu .= $this->addFrontEndMenu(AmosSondaggi::t('amossondaggi','Gestione sondaggi'), AmosSondaggi::toUrlModule('/sondaggi'));
        } else {
            $menu .= $this->addFrontEndMenu(AmosSondaggi::t('amossondaggi', '#menu_front_sondaggi'),
                AmosSondaggi::toUrlModule('/sondaggi'));
        }
        return $menu;
    }

    /**
     * @return array
     */
    public function getIndexActions()
    {
        return [
            'sondaggi/index',
            'pubblicazione/all',
            'pubblicazione/own',
            'pubblicazione/own-interest',
            'pubblicazione/all-admin',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'sondaggi' => '/sondaggi/pubblicazione/own-interest',
            'sondaggi-domande' => '/sondaggi/pubblicazione/own-interest',
            'sondaggi-domande-pagine' => '/sondaggi/pubblicazione/own-interest',
            'sondaggi-domande-tipologie' => '/sondaggi/pubblicazione/own-interest',
            'sondaggi-risposte' => '/sondaggi/pubblicazione/own-interest',
            'sondaggi-risposte-predefinite' => '/sondaggi/pubblicazione/own-interest',
            'pubblicazione' => '/sondaggi/pubblicazione/own-interest',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'sondaggi' => '/sondaggi/pubblicazione/all',
            'sondaggi-domande' => '/sondaggi/pubblicazione/all',
            'sondaggi-domande-pagine' => '/sondaggi/pubblicazione/all',
            'sondaggi-domande-tipologie' => '/sondaggi/pubblicazione/all',
            'sondaggi-risposte' => '/sondaggi/pubblicazione/all',
            'sondaggi-risposte-predefinite' => '/sondaggi/pubblicazione/all',
            'pubblicazione' => '/sondaggi/pubblicazione/all',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        $names = [
            'sondaggi' => self::t('amosdocumenti', "Sondaggi"),
            'pubblicazione' => self::t('amosdocumenti', "Sondaggi"),
            'sondaggi-domande' => self::t('amosdocumenti', "Sondaggi"),
            'sondaggi-domande-pagine' => self::t('amosdocumenti', "Sondaggi"),
            'sondaggi-domande-tipologie' => self::t('amosdocumenti', "Sondaggi"),
            'sondaggi-risposte' => self::t('amosdocumenti', "Sondaggi"),
            'sondaggi-risposte-predefinite' => self::t('amosdocumenti', "Sondaggi"),
        ];

        return $names;
    }

    /**
     * Metodo basato sul parametro "activatePoolRoles"
     *
     * Se non è impostato il parametro, l'utente ha il permesso
     * Se impostato controllo il ruolo
     *
     * Quindi se l'elenco di ruoli che possono attivare il sondaggio è impostato e l'utente non ha il ruolo, permesso negato.
     *
     * @return bool
     */
    public function currentUserCanActivatePool()
    {
        // Se non impostato il parametro allora il passaggio di stato è lasciato al permesso sul ruolo del passaggio di stato
        if (empty($this->activatePoolRoles)) {
            return true;
        }

        foreach ($this->activatePoolRoles as $role) {
            if (Yii::$app->user->can($role)) {
                return true;
            }
        }

        return false;
    }
}
