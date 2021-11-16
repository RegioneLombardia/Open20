<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility
 * @category   CategoryName
 */


namespace amos\rss;

use Yii;
use open20\amos\core\module\AmosModule;
use yii\helpers\ArrayHelper;

/**
 * /**
 * due module definition class
 */
class Module extends AmosModule
{
    public $controllerNamespace = 'amos\rss\controllers';
    private $title;
    private $url;
    private $description;
    private $modelsEnabled = [];
    private $federationUrls = [];
    private $feedsUrls = [];
    private $feedsScope = [];
    private $limitRss = 3;
    private $category;

    
    /**
     * 
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * 
     * @param string $title
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * 
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    /**
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    /**
     * 
     * @return array
     */
    public function getModelsEnabled()
    {
        return $this->modelsEnabled;
    }
    
    /**
     * 
     * @param array $models
     */
    public function setModelsEnabled(array $models)
    {
        $this->modelsEnabled = $models;
    }
    
    /**
     * 
     * @param array $federations
     */
    public function setFederationUrls(array $federations)
    {
        $this->federationUrls = $federations;
    }
    
    /**
     * 
     * @return array
     */
    public function getFederationUrls()
    {
        return $this->federationUrls;
    }

/**
     * 
     * @param array $feedsurls
     */
    public function setFeedsScope(array $feedsScope)
    {
        $this->feedsScope = $feedsScope;
    }
    
    /**
     * 
     * @return array
     */
    public function getFeedsScope()
    {
        return $this->feedsScope;
    }
    
    /**
     * 
     * @param array $feedsurls
     */
    public function setFeedsUrls(array $feedsurls)
    {
        $this->feedsUrls = $feedsurls;
    }
    
    /**
     * 
     * @return array
     */
    public function getFeedsUrls()
    {
        return $this->feedsUrls;
    }




    
    /**
     * 
     * @return integer
     */
    public function getLimitRss(){
        return $this->limitRss;
    }
    
    /**
     * 
     * @param type $limit
     */
    public function setLimitRss($limit){
        $this->limitRss = $limit;
    }

    /**
     *
     * @param type $id
     * @param type $parent
     * @param type $config
     */
    public function __construct($id, $parent = null, $config = array()) {
        $this->title = Yii::$app->name;
        $this->url = Yii::$app->params['platform']['backendUrl'] . "/rss/rss/rss";
        $this->description = Module::t('amosrss','#rss_platform_description');
        parent::__construct($id, $parent, $config);
    }

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        //Let console working right
        $this->setControllerNameSpace(\Yii::$app);

        //Configuration
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');

        //Setup config
        \Yii::configure($this, ArrayHelper::merge($config, $this));
        $tasksModule = $this->getModule('manage-tasks');
        $layoutModule = \Yii::$app->getModule('layout');

        if ($tasksModule && $layoutModule) {
            \Yii::$app->setLayoutPath($layoutModule->layoutPath);
        }
        $this->modelsEnabled = array_unique($this->modelsEnabled);
        $this->federationUrls = array_unique($this->federationUrls);
        $this->feedsUrls = array_unique($this->feedsUrls);
    }

    public function getWidgetIcons()
    {
        return [
        ];
    }

    public function getWidgetGraphics()
    {
        return [
        ];
    }

    /**
     * @return string
     */
    public static function getModuleName()
    {
        return 'rss';
    }

    /**
     * Get default models
     * @return array
     */
    protected function getDefaultModels()
    {
        return [
        ];
    }

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        $this->setControllerNameSpace($app);
    }


    /**
     * @param \yii\console\Application $app
     */
    private function setControllerNameSpace($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'amos\rss\commands';
        }
    }
}