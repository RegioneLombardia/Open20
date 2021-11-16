<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\discussioni\widgets\graphics\WidgetGraphicsDiscussioniInEvidenza;
use open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare;
use Yii;
use yii\console\Application;

/**
 * Class AmosDiscussioni
 * @package open20\amos\discussioni
 */
class AmosDiscussioni extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface
{
   
    /**
     * @var bool $disableComments disable comments
     */
    public $disableComments = false;

    /**
     * @var array
     */
    public $viewPathEmailSummary = [
        'open20\amos\discussioni\models\DiscussioniTopic' => '@vendor/open20/amos-discussioni/src/views/email/notify_summary'
    ];

    public $viewPathEmailSummaryNetwork = [
        'open20\amos\discussioni\models\DiscussioniTopic' => '@vendor/open20/amos-discussioni/src/views/email/notify_summary_network'
    ];
    
    const
        MAX_LAST_DISCUSSION_ON_DASHBOARD = 3;

    /**
     * 
     */
    public
    // @var string $controllerNamespace the controller namespace
        $controllerNamespace = 'open20\amos\discussioni\controllers',
        $geolocalEnabled = false,
        $geolocalLatColumn = 'lat',
        $geolocalLngColumn = 'lng',
        $geolocalRadius = '5000',
        $name = 'Discussioni',
        // @var bool $notifyOnlyContributors
        $notifyOnlyContributors = true,
        // @var array $defaultListViews This set the default order for the views in lists
        $defaultListViews = ['list'/* , 'icon' */, 'grid'],
        $enable_foreground = false,
        $foreground_permission = 'DISCUSSION_FOREGROUD_PERMISSION',
        // @var string
        $defaultWidgetIndexUrl = '/discussioni/discussioni-topic/own-interest-discussions';

    /*
     * @var bool disableStandardWorkflow Disable standard worflow, direct publish
     */
    public $disableStandardWorkflow = false;

   /**
    * @var bool values ad the same meaning of news module
    */ 
    public 
       $site_featured_enabled = false;
   
    public 
        $site_publish_enabled = false;
    
    public
        $publication_always_enabled = false;
   
   
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $this->controllerNamespace = 'open20\amos\discussioni\commands\controllers';
            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/commands/controllers', __DIR__ . '/commands/controllers');
            \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'commands' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        }
    }

    /**
     *
     */
    public function init()
    {
        parent::init();

        if (\Yii::$app instanceof Application) {
            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/commands', __DIR__ . '/commands/');
            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
            //aggiunge le configurazioni trovate nel file config/config.php
            Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        } else {
            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
            //aggiunge le configurazioni trovate nel file config/config.php
            Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        }
    }

    /**
     * @return string - The name of the module
     */
    public static function getModuleName()
    {
        return "discussioni";
    }

    /**
     * 
     * @return type
     */
    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\DiscussioniTopicSearch';
    }

    /**
     * 
     * @return type
     */
    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\DiscussioniTopic';
    }

    /**
     * 
     * @return string
     */
    public static function getModuleIconName()
    {
        return 'comment';
    }

    /**
     * @return boolean
     */
    public function isGeolocalEnabled()
    {
        return $this->geolocalEnabled;
    }

    /**
     * @param boolean $geolocalEnabled
     */
    public function setGeolocalEnabled($geolocalEnabled)
    {
        $this->geolocalEnabled = $geolocalEnabled;
    }

    /**
     * @return string
     */
    public function getGeolocalLatColumn()
    {
        return $this->geolocalLatColumn;
    }

    /**
     * @param string $geolocalLatColumn
     */
    public function setGeolocalLatColumn($geolocalLatColumn)
    {
        $this->geolocalLatColumn = $geolocalLatColumn;
    }

    /**
     * @return string
     */
    public function getGeolocalLngColumn()
    {
        return $this->geolocalLngColumn;
    }

    /**
     * @param string $geolocalLngColumn
     */
    public function setGeolocalLngColumn($geolocalLngColumn)
    {
        $this->geolocalLngColumn = $geolocalLngColumn;
    }

    /**
     * @return string
     */
    public function getGeolocalRadius()
    {
        return $this->geolocalRadius;
    }

    /**
     * @param string $geolocalRadius
     */
    public function setGeolocalRadius($geolocalRadius)
    {
        $this->geolocalRadius = $geolocalRadius;
    }

    /**
     * @return array: classname of the graphic widgets
     */
    public function getWidgetGraphics()
    {
        return [
            WidgetGraphicsUltimeDiscussioni::className(),
            WidgetGraphicsDiscussioniInEvidenza::className(),
        ];
    }

    /**
     * @return array: classname of the icon widgets
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconDiscussioniTopic::className(),
            WidgetIconDiscussioniTopicCreatedBy::className(),
            WidgetIconDiscussioniTopicDaValidare::className(),
        ];
    }

    /**
     * @return array
     */
    protected function getDefaultModels()
    {
        return [
            'DiscussioniTopic' => __NAMESPACE__ . '\\' . 'models\DiscussioniTopic',
            'DiscussioniCommenti' => __NAMESPACE__ . '\\' . 'models\DiscussioniCommenti',
            'DiscussioniRisposte' => __NAMESPACE__ . '\\' . 'models\DiscussioniRisposte',
        ];
    }

    /**
     * This method return the session key that must be used to add in session
     * the url from the user have started the content creation.
     * @return string
     */
    public static function beginCreateNewSessionKey()
    {
        return 'beginCreateNewUrl_' . self::getModuleName();
    }

}
