<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\elasticsearch\behaviors\ElasticSearchBehavior;
use open20\elasticsearch\transformer\TransformerManagers;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Module extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BootstrapInterface {

    public static $CONFIG_FOLDER = 'config';
    private static $client = null;
    public $behaviorName = 'eleasticsearch';
    public $eventsToAttach = [
        ActiveRecord::EVENT_BEFORE_DELETE,
        ActiveRecord::EVENT_BEFORE_INSERT,
        ActiveRecord::EVENT_BEFORE_UPDATE
    ];
    public $hosts = [
        'http://localhost',
    ];
    public $modelsEnabled = [];
    public $transformerManager = null;
    public $useFinalSpecial = false;

    /**
     * [
      'number_of_replicas' => 0,
      'refresh_interval' => -1
      ];
     * @var array
     */
    public $defualtIndexSettings = null;
    public $indexes_setting = [];
    public $indexes_mapping = [];
    public $foldingClass = 'open20\elasticsearch\models\folding\Folding';
    private $folding = null;
    public $versionElastic = '6';

    /**
     * @var array
     */
    public $history = false;

    /**
     * 
     * @var bool
     */
    public $enableCwh = false;

    /**
     * 
     * @var bool
     */
    public $filterCwhByUserTags = false;

    /**
     * 
     * @var type
     */
    public $indexPrefixName = "";

    /**
     *
     * @var string
     */
    public $cssGetCrawlerHtmlFilter = 'body';

    /**
     *
     * @var bool
     */
    public $enableI18N = false;

    /**
     * 
     * @var bool
     */
    public $indexDraftNavItem = false;

    /**
     * 
     * @var bool
     */
    public $indexProtectedNavItem = false;

    public function getFolding() {
        return $this->folding;
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName() {
        return 'elasticsearch';
    }

    public function getWidgetIcons() {
        return [];
    }

    public function getWidgetGraphics() {
        return [];
    }

    /**
     * Get default model classes
     */
    protected function getDefaultModels() {
        return [
            'ElasticModel' => __NAMESPACE__ . '\\' . 'models\ElasticModel',
            'ElasticModelSearch' => __NAMESPACE__ . '\\' . 'models\search\ElasticModelSearch',
        ];
    }

    public static function getModelClassName() {
        return Module::instance()->model('ElasticModel');
    }

    public static function getModelSearchClassName() {
        return Module::instance()->model('ElasticModelSearch');
    }

    public static function getModuleIconName() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        \Yii::setAlias('@open20/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');

        //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        Yii::configure($this, ArrayHelper::merge($config, $this));

        $this->buildClientElasticSearch();
        $this->transformerManager = new TransformerManagers($this);
        $this->folding = \Yii::createObject($this->foldingClass);
        if (strlen($this->indexPrefixName) < 3) {
            throw new \Exception(Module::getModuleName() . ' module param: indexPrefixName must be at least 3 characters long!');
        }
    }

    /**
     * 
     * @return Client
     */
    public function getClient() {
        return self::$client;
    }

    /**
     * 
     * @param Client $client
     */
    public function setClient(Client $client) {
        self::$client = $client;
    }

    /**
     * 
     */
    protected function buildClientElasticSearch() {
        if (is_null($this->client)) {
            $clientBuilder = ClientBuilder::create();   // Instantiate a new ClientBuilder
            $clientBuilder->setHosts($this->hosts);     // Set the hosts
            $this->client = $clientBuilder->build();
        }
    }

    /**
     * 
     * @param type $app
     */
    public function bootstrap($app) {

        foreach ($this->modelsEnabled as $model => $transformer) {
            foreach ($this->eventsToAttach as $attach) {
                Event::on($model, $attach, function ($event) {
                    $this->attachElasticSearchBehavior($event->sender);
                });
            }
        }
    }

    /**
     * 
     * @param type $reciver
     */
    public function attachElasticSearchBehavior($reciver) {
        if (is_null($reciver->getBehavior($this->behaviorName))) {
            $reciver->attachBehavior($this->behaviorName, ElasticSearchBehavior::className());
        }
    }

}
