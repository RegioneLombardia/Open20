<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation
 * @category   CategoryName
 */

namespace open20\amos\translation;

use open20\amos\core\module\Module;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\record\Record;
use yii\web\Application;
use yii\base\BootstrapInterface;
use Yii;
use yii\base\Event;
use yii\helpers\StringHelper;
use yii\helpers\FileHelper;

/**
 * Class AmosWorkflow
 * @package open20\amos\workflow
 */
class AmosTranslation extends AmosModule implements BootstrapInterface
{
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout              = 'main';
    public $name                = 'Translation';
    public $newFileMode         = 0666;
    public $newDirMode          = 0777;
    public $languageField       = 'language';
    public $controllerNamespace = 'open20\amos\translation\controllers';

    /**
     * It set the language source, if is set every saved record is copied in the translation language default
     * @var type $defaultLanguage
     */
    public $defaultLanguage;

    /**
     * If is set it sets the translation language when the translation request is not present
     * @var type $defaultTranslationLanguage
     */
    public $defaultTranslationLanguage;
    public $enableUserLanguage = true;

    /**
     * If the value is true it disable all the permission on the record translation
     * @var type $byPassPermissionInlineTranslation
     */
    public $byPassPermissionInlineTranslation = true;

    /**
     * If set to true it enables the display of the translatable fields in the forms
     * @var boolean $enableLabelTranslationField
     */
    public $enableLabelTranslationField = false;

    /**
     * Template of translation field in the form, near the label
     * @var string $templateTranslationField
     */
    public $templateTranslationField = '{translation}';

    /**
     * Template of translation alt field in the form, near the $templateTranslationField
     * @var string $templateTranslationAltField
     */
    public $templateTranslationAltField = '{altTranslation}';

    /**
     * This string will be parsed by the "eval()" function instead of $tempalteTransaltionField, by default {translation}
     * @var string $translationLabelField
     */
    public $translationLabelField = 'strtoupper(substr(\Yii::$app->language, 0, 2));';

    /**
     * This string will be parsed by the "eval()" function instead of $templateTranslationAltField, by default {altTranslation}
     * @var string $translationLabelAltField
     */
    public $translationLabelAltField = '\Yii::t("amostranslation", "Testo traducibile direttamente scrivendo in questo campo, tradurrai nella lingua selezionata, la visualizzazione attuale è in");';

    /**
     * This string is the html code that will be used in the labels to represent a translatable field
     * @var string $labelTranslationField
     */
    public $labelTranslationField = ' (<span class="label_translation am am-translate" title="{altTranslation} {translation}"> - {translation}</span>)';

    /**
     * It is the default user language
     * @var string $defaultUserLanguage
     */
    public $defaultUserLanguage         = 'en-GB';
    public $nameCreatedBy               = 'nome';
    public $surnameCreatedBy            = 'cognome';
    public $supportedLanguages          = [];
    public $secureCookie                = true;
    public $enableCookieFor2LevelDomain = false;

    /**
     * Add this module to /common/config/main.php
     * ```php
     * 'modules' => [
     * 'translation' => [
     *      'class' => 'open20\amos\translation\AmosTranslation',
     *      'translationBootstrap' => [
     *          'configuration' => [
     *              'translationLabels' => [
     *                  'classBehavior' => \lajax\translatemanager\behaviors\TranslateBehavior::className(),
     *                  'models' => [
     *                      [
     *                      'namespace' => 'cornernote\workflow\manager\models\Status',
     *                      //'connection' => 'db', //if not set it use 'db'
     *                      //'classBehavior' => null,//if not set it use default classBehavior 'lajax\translatemanager\behaviors\TranslateBehavior'
     *                      'attributes' => ['field'],
     *                      ],
     *                  ],
     *              ],
     *              'translationContents' => [
     *                  'classBehavior' => \open20\amos\translation\behaviors\TranslateableBehavior::className(),
     *                  'models' => [
     *                      [
     *                      'namespace' => 'backend\modules\id_plugin\models\NameModel',
     *                      //'connection' => 'db', //if not set it use 'db'
     *                      //'classBehavior' => null,//if not set it use default classBehavior 'open20\amos\translation\behaviors\TranslateableBehavior'
     *                      //'enableWorkflow' => false,//if not set it use default configuration of the plugin
     *                      //'workflow' => 'AmosTranslationWorkflow',
     *                      //'view' => '@backend/modules/module/controller/view,
     *                      //'forceTranslation' => true,
     *                      'rteAttributes' => ['field'], //only if $this->enableRTE is true it set the RTE for the specified attributes
     *                      'attributes' => ['field'],
     *                      'plugin' => 'id_plugin'
     *                      ]
     *                  ],
     *              ],
     *          ],
     *      ],
     *      'module_translation_labels' => 'translatemanager',
     *      //'module_translation_labels_options' => [];//all the options of the translatemanager
     *      'components' => [
     *          'translatemanager' => [
     *              'class' => 'lajax\translatemanager\Component'
     *          ],
     *      ],
     * ],
     * ],
     * ```
     *
     * Add to bootstrap array in the /backend/config/main.php and/or /frontend/config/main.php the follow class
     * ```php
     *  'bootstrap' => [
     *      'translation',
     *      'open20\amos\translation\bootstrap\EventActiveRecordBootstrap',//fot the translation of the records
     *      'open20\amos\translation\bootstrap\EventViewBootstrap',//for the widget in the view file
     * ],
     * ```
     */
    public $translationBootstrap;
    public $modules;
    public $forceTranslation         = true;
    public $pathsForceTranslation    = ['@frontend'];
    public $eventViewBlackListViews  = [];
    public $eventViewBlackListModels = [];
    public $eventViewWhiteListParts  = ['_form'];
    public $enableWidgetView         = true;
    public $widgetViewFile           = '@vendor/open20/amos-translation/src/views/default/language_status.php';
    public $actionLanguage           = '/translation/default/language';
    public $numberGridViewField      = 3;
    public $enableRTE                = true;
    public $clientOptionsRTE         = [];

    /**
     * Model base class will be generated
     * @var string
     */
    public $modelBaseClass        = 'open20\\amos\\core\\record\\Record';
    public $modelNs               = "backend\\models\\translations";
    public $modelGenerateRelation = 'all';

    /**
     * Enable or disable the workflow on the Active Record
     * @var boolean
     */
    public $enableWorkflow = true;

    /**
     * Workflow behavior name, default is 'workflow'
     * @var string
     */
    public $workflowBehavior                 = 'workflow';
    public $statusWorkflowApproved           = 'AmosTranslationWorkflow/APPROVED';
    public $statusWorkflowInitial            = 'AmosTranslationWorkflow/DRAFT';
    public $module_translation_labels;
    public $module_translation_labels_options;
    public $components                       = [];
    public $dbTranslation                    = 'db';
    public $dbSource                         = 'db';
    public $defaultTypeAttributesToTranslate = ['string', 'text', 'char'];
    public $systemBlackListAttributes        = ['id', 'status', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by',
        'deleted_by'];

    /**
     * The cache object or the ID of the cache application component that is used for query caching
     * @var type
     */
    public $queryCache = 'cache';

    /**
     * If true, the cache of the Query or the ActiveQuery is active
     * @var boolean $cached
     */
    public $cached = true;

    /**
     * The time of the cache duration, after this time the cache will be invalidated
     * @var integer $cacheDuration
     */
    public $cacheDuration = 86400;

    /**
     * Name of the property the modules will use for the configuration of the fields of the db to be translated
     * @var string $propertyModule
     */
    public $propertyModule = 'db_fields_translation';

    /**
     * Name of the file that will be generated with the configurations of the fields of the db to be translated
     * @var string $fileNameDbConfFields
     */
    public $fileNameDbConfFields = '__db_fields_translation';

    /**
     * Blacklist of the modules for which it will not occur the existence of field configurations of the db to be translated
     * @var array $moduleBlackListForDbConfFields
     */
    public $moduleBlackListForDbConfFields = ['translation', 'audit', 'debug'];

    /**
     * @var string $modelOwnerPlatformTranslation
     */
    public $modelOwnerPlatformTranslation     = 'lajax\translatemanager\models\LanguageTranslate';
    public $modelOwnerPlatformTrIdField       = 'id';
    public $modelOwnerPlatformTrLanguageField = 'language';
    public $availableLanguages                = null;

    public static function getModuleName()
    {
        return "translation";
    }

    public function init()
    {
        parent::init();

        \Yii::setAlias('@open20/amos/'.static::getModuleName().'/controllers', __DIR__.'/controllers');
// initialize the module with the configuration loaded from config.php
        Yii::configure($this, require(__DIR__.DIRECTORY_SEPARATOR.self::$CONFIG_FOLDER.DIRECTORY_SEPARATOR.'config.php'));
        $this->configTranslationLabelsByModules();
        $this->module_translation_labels_options = $this->getModuleTranslationLabelsOptions();


        \Yii::$app->setModule($this->module_translation_labels, $this->module_translation_labels_options);
        if (\Yii::$app instanceof Application) {
            \Yii::$app->setComponents($this->components);
        }
        $this->generateTranslationTables();
        $this->generateTranslationModels();
        $this->name = 'Traduzioni';
    }

    public function getWidgetIcons()
    {
        return [
//            widgets\icons\WidgetIconTranslation::className(),
            widgets\icons\WidgetIconTrContents::className(),
            widgets\icons\WidgetIconTrPlatform::className(),
            //widgets\icons\WidgetIconTrLanguage::className(),//will to ability when the view of the translate of platform are completed
            widgets\icons\WidgetIconTrOptimize::className(),
            widgets\icons\WidgetIconTrScan::className(),
        ];
    }

    public function getWidgetGraphics()
    {
        return [];
    }

    public function getModuleTranslationLabelsOptions()
    {
        $configuration                            = [];
        $configuration['class']                   = (isset($this->module_translation_labels_options['class']) ? $this->module_translation_labels_options['class']
                : 'lajax\translatemanager\Module');
        $configuration['root']                    = (isset($this->module_translation_labels_options['root']) ? $this->module_translation_labels_options['root']
                : [
            '@app',
            '@backend',
            '@frontend',
            '@vendor/open20/',
        ]);
        $configuration['scanRootParentDirectory'] = (isset($this->module_translation_labels_options['scanRootParentDirectory'])
                ? $this->module_translation_labels_options['scanRootParentDirectory'] : true);
        $configuration['layout']                  = (isset($this->module_translation_labels_options['layout']) ? $this->module_translation_labels_options['layout']
                : '@vendor/open20/amos-layout/src/views/layouts/main');
        $configuration['allowedIPs']              = (isset($this->module_translation_labels_options['allowedIPs']) ? $this->module_translation_labels_options['allowedIPs']
                : ['*']);
        $configuration['roles']                   = (isset($this->module_translation_labels_options['roles']) ? $this->module_translation_labels_options['roles']
                : ['ADMIN']);
        $configuration['tmpDir']                  = (isset($this->module_translation_labels_options['tmpDir']) ? $this->module_translation_labels_options['tmpDir']
                : '@runtime');
        $configuration['phpTranslators']          = (isset($this->module_translation_labels_options['phpTranslators']) ? $this->module_translation_labels_options['phpTranslators']
                : [
            '::t',
            '::tText',
            '::tHtml',
        ]);
        $configuration['jsTranslators']           = (isset($this->module_translation_labels_options['jsTranslators']) ? $this->module_translation_labels_options['jsTranslators']
                : ['lajax.t']);
        $configuration['patterns']                = (isset($this->module_translation_labels_options['patterns']) ? $this->module_translation_labels_options['patterns']
                : ['*.js', '*.php']);
        $configuration['ignoredCategories']       = (isset($this->module_translation_labels_options['ignoredCategories'])
                ? $this->module_translation_labels_options['ignoredCategories'] : ['yii']);
        $configuration['ignoredItems']            = (isset($this->module_translation_labels_options['ignoredItems']) ? $this->module_translation_labels_options['ignoredItems']
                : ['config']);
        $configuration['scanTimeLimit']           = (isset($this->module_translation_labels_options['scanTimeLimit']) ? $this->module_translation_labels_options['scanTimeLimit']
                : null);
        $configuration['searchEmptyCommand']      = (isset($this->module_translation_labels_options['searchEmptyCommand'])
                ? $this->module_translation_labels_options['searchEmptyCommand'] : '!');
        $configuration['defaultExportStatus']     = (isset($this->module_translation_labels_options['defaultExportStatus'])
                ? $this->module_translation_labels_options['defaultExportStatus'] : 1);
        $configuration['defaultExportFormat']     = (isset($this->module_translation_labels_options['defaultExportFormat'])
                ? $this->module_translation_labels_options['defaultExportFormat'] : 'json');
        $configuration['tables']                  = (isset($this->module_translation_labels_options['tables']) ? $this->module_translation_labels_options['tables']
                : $this->getArrayDbTranslation());
        $configuration['scanners']                = (isset($this->module_translation_labels_options['scanners']) ? $this->module_translation_labels_options['scanners']
                : [
// define this if you need to override default scanners (below)
            '\lajax\translatemanager\services\scanners\ScannerPhpFunction',
            '\lajax\translatemanager\services\scanners\ScannerPhpArray',
            '\lajax\translatemanager\services\scanners\ScannerJavaScriptFunction',
            '\lajax\translatemanager\services\scanners\ScannerDatabase',
        ]);

        return $configuration;
    }

    public function configTranslationLabelsByModules()
    {
        $config = $this->translationBootstrap;

        $dbConfFieldsPath = \Yii::getAlias('@'.str_replace('\\', '/', $this->modelNs)).'/'."{$this->fileNameDbConfFields}".'.php';
        if (file_exists($dbConfFieldsPath)) {
            $this->translationBootstrap['configuration']['translationLabels']['models'] = array_merge((!empty($config['configuration']['translationLabels']['models'])
                    ? $config['configuration']['translationLabels']['models'] : []), require($dbConfFieldsPath));
        }
    }

    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $preferredLanguage = !\Yii::$app->user->isGuest ? $this->getLanguageCookie() : null;
            if (empty($preferredLanguage)) {
                if ($this->enableUserLanguage == true && !\Yii::$app->getUser()->isGuest) {
                    $preferredLanguage = $this->getUserLanguage();
                } else if (\Yii::$app->getUser()->isGuest) {
                    $preferredLanguage = $this->getLanguageCookie();
                    if (empty($preferredLanguage)) {
                        $preferredLanguage = $this->getBrowserLanguage();
                    }
                } else {
                    $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);
                }
            }
            $app->language = $preferredLanguage;

            if ($this->enableUserLanguage == true) {
                $this->setUserLanguage($app->language);
            }
        }
        if (empty($app->language)) {
            $app->language = $this->defaultUserLanguage;
        }
    }

    /**
     * @return null|string
     */
    public function getLanguageCookie()
    {
        if ($this->secureCookie) {
            $preferredLanguage = (isset(\Yii::$app->request->cookies['language'])) ? (string) \Yii::$app->request->cookies['language']
                    : null;
        } else {
            $preferredLanguage = !empty($_COOKIE['language']) ? $_COOKIE['language'] : null;
        }
        return $preferredLanguage;
    }

    /**
     * Set user language
     * @param string $language For example 'it-IT', 'en-GB', 'en-US'
     */
    public function setUserLanguage($language)
    {
        $available = $this->getAvailableLanguages();
        if (!empty($available) && array_key_exists($language, $available)) {
            if (\Yii::$app instanceof Application && !\Yii::$app->getUser()->isGuest) {
                $userId     = \Yii::$app->getUser()->getId();
                $preference = models\TranslationUserPreference::find()->andWhere(['user_id' => $userId]);
                if ($preference->count()) {
                    $model       = $preference->one();
                    $model->lang = $language;
                    $model->save(false);
                } else {
                    $newPreference          = new models\TranslationUserPreference();
                    $newPreference->user_id = $userId;
                    $newPreference->lang    = $language;
                    $newPreference->validate();
                    $newPreference->save(false);
                }
            }
            \Yii::$app->language = $language;
        }
        if (empty(\Yii::$app->language)) {
            \Yii::$app->language = $this->defaultUserLanguage;
        }
    }

    /**
     * Set guest language
     * @return string $language For example 'it-IT', 'en-GB', 'en-US'
     */
    public function getGuestLanguage()
    {
        $preferredLanguage = null;
        $language          = null;
        if (\Yii::$app instanceof Application) {
            $preferredLanguage = $this->getLanguageCookie();
            if (empty($preferredLanguage)) {
                $preferredLanguage = $this->getLanguageCookie();
                if (empty($preferredLanguage)) {
                    $preferredLanguage = $this->getBrowserLanguage();
                }
                if (empty($preferredLanguage)) {
                    $preferredLanguage = \Yii::$app->request->getPreferredLanguage($this->supportedLanguages);
                }
            }
            $language = $preferredLanguage;
        }
        if (empty($language)) {
            $language = $this->defaultUserLanguage;
        }

        return $language;
    }

    /**
     *
     * @param integer|null $userId
     * @return string
     */
    public function getUserLanguage($userId = null)
    {
        $language  = null;
        $available = $this->getAvailableLanguages();
        if ($userId != null) {
            $lang1 = $this->getUserLanguagePreference($userId);
            if (array_key_exists($lang1, $available)) {
                $language = $available[$lang1];
            }
        } else if (\Yii::$app instanceof Application && !\Yii::$app->getUser()->isGuest) {
            $userId = \Yii::$app->getUser()->getId();
            if ($userId != null) {
                $lang2 = $this->getUserLanguagePreference($userId);
                if (array_key_exists($lang2, $available)) {
                    $language = $available[$lang2];
                }
            }
        }

        if (\Yii::$app instanceof Application && $language == null) {
            $language = $this->getBrowserLanguage();
        }

        if ($language == null && !empty($this->defaultUserLanguage)) {
            $language = $this->defaultUserLanguage;
        } else if (!empty(\Yii::$app->language) && $language == null) {
            $language = \Yii::$app->language;
        }

        return $language;
    }

    /**
     * Get browser language
     * @return string Language
     */
    public function getBrowserLanguage()
    {
        $available = $this->getAvailableLanguages();
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            if (!empty($available)) {
                foreach ($langs as $lang) {
                    $lang = explode(';', $lang);
                    if (is_array($lang)) {
                        foreach ($lang as $lng) {
                            if (array_key_exists($lng, $available)) {
                                return $available[$lng];
                            }
                        }
                    } else {
                        if (array_key_exists($lang, $available)) {
                            return $available[$lang];
                        }
                    }
                }
            }
        }
        return $this->defaultUserLanguage;
    }

    /**
     *
     * @param boolean $beta_language Default false
     * @return array
     */
    public function getAvailableLanguages($beta_language = false)
    {
        if (is_null($this->availableLanguages)) {
            $languages                = models\TranslationConf::getStaticAllActiveLanguages(true, null, $beta_language)->asArray()->all();
            $this->availableLanguages = [];
            if (!empty($languages)) {
                foreach ($languages as $availableLang) {
                    $this->availableLanguages[$availableLang['language']]    = $availableLang['language_id'];
                    $this->availableLanguages[$availableLang['language_id']] = $availableLang['language_id'];
                }
            }
        }
        return $this->availableLanguages;
    }

    /**
     *
     * @param integer $userId
     * @return string|null
     */
    private function getUserLanguagePreference($userId)
    {
        $preference = models\TranslationUserPreference::find()->andWhere(['user_id' => $userId]);
        if ($preference->count() && !empty($preference->one()->lang)) {
            return $preference->one()->lang;
        }
        return null;
    }

    /**
     *
     * @param string $language
     */
    public function setAppLanguage($language)
    {
        \Yii::$app->language = $language;
    }

    /**
     *
     * @return array
     */
    public function getDefaultModels()
    {
        return [];
    }

    /**
     * 
     * @param boolean $force
     * @param boolean $forceModels
     */
    public function generateTranslationTables($force = false, $forceModels = false)
    {
        $models = [];
        if (!empty($this->translationBootstrap['configuration']['translationContents']['models'])) {
            foreach ($this->translationBootstrap['configuration']['translationContents']['models'] as $model) {
                $isCreated = $this->executeSql($model['namespace'], $force);
                if ($isCreated !== false || $forceModels === true) {
                    $models[] = ['namespace' => $model['namespace'], 'plugin' => (!empty($model['plugin']) ? $model['plugin']
                            : null)];
                }
            }
        }
        if (!empty($models)) {
            foreach ($models as $value) {
                $conf = models\TranslationConf::findOne(['namespace' => $value['namespace']]);
                if ($conf) {
                    $conf->plugin = $value['plugin'];
                    $conf->fields = serialize($this->getModelAttributes($value['namespace'], true));
                    $conf->save(false);
                } else {
                    $conf            = new models\TranslationConf();
                    $conf->namespace = $value['namespace'];
                    $conf->plugin    = $value['plugin'];
                    $conf->fields    = serialize($this->getModelAttributes($value['namespace'], true));
                    $conf->save(false);
                }
            }
        }
    }

    /**
     *
     * @param boolean $force
     */
    public function generateTranslationModels($force = false)
    {
        $models         = [];
        $files          = [];
        $workflow       = 'AmosTranslationWorkflow';
        $enableWorkflow = false;
        if (\Yii::$app->db->getTableSchema(models\TranslationConf::tableName()) !== null) {
            $verifyModels = models\TranslationConf::find();
            if ($force == false) {
                $verifyModels->andWhere(['model_generated' => 0]);
            }
            $configuration = (isset($this->translationBootstrap['configuration']['translationContents']['models']) ? $this->translationBootstrap['configuration']['translationContents']['models']
                    : '');
            foreach ((array) $configuration as $model) {
                if (isset($model['enableWorkflow'])) {
                    if ($model['enableWorkflow'] == true) {
                        $enableWorkflow = true;
                    }
                } else {
                    $enableWorkflow = $this->enableWorkflow;
                }
                if (!empty($model['workflow'])) {
                    $workflow = $model['workflow'];
                }
            }
            if ($verifyModels->count()) {
                foreach ($verifyModels->all() as $model) {
                    $namespace        = $model->namespace;
                    $modelClass       = StringHelper::basename($namespace)."Translation";
                    $table            = $namespace::tableName();
                    $tableTranslation = "{$table}__translation";
                    $tableSchema      = \Yii::$app->{$this->dbTranslation}->getTableSchema($tableTranslation, true);

                    if (!empty($tableSchema)) {
                        $generator                     = new components\Generator();
                        $generator->tableName          = $tableTranslation;
                        $generator->baseClass          = $this->modelBaseClass;
                        $generator->generatedClassName = $modelClass;
                        $generator->ns                 = $this->modelNs;
                        $generator->enableI18N         = true;
                        $generator->messageCategory    = 'amostranslation';
                        $generator->classnameTarget    = str_replace(StringHelper::basename($namespace), '', $namespace);
                        if ($enableWorkflow == true) {
                            $generator->workflow = $workflow;
                        }
                        $generator->db                         = $this->dbTranslation;
                        $generator->generateRelations          = $this->modelGenerateRelation;
                        $generator->generateLabelsFromComments = true;
                        $generator->queryNs                    = $this->modelNs;
                        $generator->modelClass                 = $this->modelNs.'\\'.$modelClass;
                        $files[]                               = $generator->generate();
                        $model->model_generated                = 1;
                        $model->save(false);
                    }
                }
            }
            if (!empty($files)) {
                foreach ($files as $file) {
                    foreach ($file as $File) {
                        $path = str_replace(StringHelper::basename($File->path), '', $File->path);
                        if (!is_dir($path)) {
                            FileHelper::createDirectory($path);
                        }
                        $handle = fopen($File->path, 'w');
                        fwrite($handle, $File->content);
                        fclose($handle);
                    }
                }
            }
        }
    }

    /**
     *
     * @param string $classname
     * @param boolean $force
     * @return boolean
     */
    protected function executeSql($classname, $force = false)
    {
        $sql    = "";
        $result = false;
        try {
            $connection = \Yii::$app->{$this->dbTranslation};
            // $connection->enableQueryCache  = false;
            // $connection->enableSchemaCache = true;
            $table      = $classname::tableName();

            if (\Yii::$app->{$this->dbTranslation}->getTableSchema("{$table}__translation", false) === NULL) {

                $sql .= "CREATE TABLE `{$table}__translation` (
                    `{$table}_id` int(11) NOT NULL,
                    `{$this->languageField}` VARCHAR(255) NOT NULL,";

                $tableSchema = \Yii::$app->{$this->dbSource}->getTableSchema($table, true);

                if (!empty($this->defaultTypeAttributesToTranslate) && !empty($tableSchema)) {
                    foreach ((array) $tableSchema->columns as $key => $value) {
                        if (in_array($value->type, $this->defaultTypeAttributesToTranslate) && !in_array($value->name,
                                $this->systemBlackListAttributes)) {
                            $sql .= "`{$value->name}` TEXT DEFAULT NULL,";
                        }
                    }
                }
                $sql .= "`status` VARCHAR(255) DEFAULT NULL,
                    `created_by` INTEGER DEFAULT NULL,
                    `updated_by` INTEGER DEFAULT NULL,
                    `deleted_by` INTEGER DEFAULT NULL,
                    `created_at` DATETIME DEFAULT NULL,
                    `updated_at` DATETIME DEFAULT NULL,
                    `deleted_at` DATETIME DEFAULT NULL) ".
                    (\Yii::$app->{$this->dbTranslation}->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
                        : null).
                    ";";
                $sql .= "ALTER TABLE `{$table}__translation`
                        ADD PRIMARY KEY (`{$table}_id`,`{$this->languageField}`);
                        ALTER TABLE `{$table}__translation`
                        ADD CONSTRAINT `fk_{$table}_id_trans` FOREIGN KEY (`{$table}_id`) REFERENCES `$table` (`{$tableSchema->primaryKey[0]}`);";

                $command = $connection->createCommand($sql);
                $result  = $command->execute();
            } else if ($force) {
                $columns = $this->getChangeAttributes($classname);
                if (!empty($columns) && count($columns)) {
                    $sql .= "ALTER TABLE `{$table}__translation` ADD COLUMN (";
                    $ind = 0;
                    foreach ($columns as $value) {
                        $sql .= ($ind == 0 ? "" : ",")."`$value` TEXT DEFAULT NULL";
                        $ind++;
                    }
                    $sql     .= ");";
                    $command = $connection->createCommand($sql);
                    $result  = $command->execute();
                }
            }
            return $result;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     *
     * @param string $classname
     * @return array
     */
    protected function getChangeAttributes($classname)
    {
        $table = $classname::tableName();

        $tableSchema            = \Yii::$app->{$this->dbSource}->getTableSchema($table, true);
        $tableSchemaTranslation = \Yii::$app->{$this->dbTranslation}->getTableSchema("{$table}__translation", true);
        $attributes             = $this->getModelAttributes($classname);
        $attributesTranslation  = $this->getModelAttributes("{$classname}Translation", true);

        return array_diff($attributes, $attributesTranslation);
    }

    public function getModelAttributes($classname, $translation = false)
    {
        $table       = $classname::tableName();
        $db          = ($translation ? $this->dbTranslation : $this->dbSource);
        $tableSchema = \Yii::$app->{$db}->getTableSchema($table, true);
        $attributes  = [];
        if (!empty($this->defaultTypeAttributesToTranslate) && !empty($tableSchema)) {
            foreach ((array) $tableSchema->columns as $key => $value) {
                if (in_array($value->type, $this->defaultTypeAttributesToTranslate) && !in_array($value->name,
                        $this->systemBlackListAttributes)) {
                    $attributes[] = $value->name;
                }
            }
        }
        return $attributes;
    }

    /**
     * @return array Array of the tables for the translatemanager plugin
     */
    public function getArrayDbTranslation()
    {
        try {
            $params             = $this->translationBootstrap;
            $arrayDbTranslation = [];
            if (!empty($params['configuration']['translationLabels']['models'])) {
                $models = $params['configuration']['translationLabels']['models'];
                foreach ($models as $model) {
                    $arrayDbTranslation[] = [
                        'connection' => (isset($model['connection']) ? $model['connection'] : 'db'),
                        'table' => $model['namespace']::tableName(),
                        'columns' => $model['attributes'],
                        'category' => (isset($model['category']) ? $model['category'] : 'database-table-name'),
                    ];
                }
            }

            return $arrayDbTranslation;
        } catch (\Exception $e) {
            return [];
        }
    }
}