<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni;

use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\discussioni\widgets\graphics\WidgetGraphicsDiscussioniInEvidenza;
use open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy;
use open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare;
use yii\base\BootstrapInterface;
use yii\console\Application;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Class AmosDiscussioni
 * @package open20\amos\discussioni
 */
class AmosDiscussioni extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface, BootstrapInterface
{
    /**
     * @var bool $disableComments disable comments
     */
    public $disableComments = false;
    
    /**
     * This param enables the search by tags
     * @var bool $searchByTags
     */
    public $searchByTags = false;
    
    const MAX_LAST_DISCUSSION_ON_DASHBOARD = 3;
    
    /**
     * @var string $controllerNamespace the controller namespace
     */
    public $controllerNamespace = 'open20\amos\discussioni\controllers';
    
    /**
     * @var bool $geolocalEnabled
     */
    public $geolocalEnabled = false;
    /**
     * @var string $geolocalLatColumn
     */
    public $geolocalLatColumn = 'lat';
    
    /**
     * @var string $geolocalLngColumn
     */
    public $geolocalLngColumn = 'lng';
    
    /**
     * @var string $geolocalRadius
     */
    public $geolocalRadius = '5000';
    
    /**
     * @var string $name
     */
    public $name = 'Discussioni';
    
    /**
     * @var bool $notifyOnlyContributors
     */
    public $notifyOnlyContributors = true;
    
    /**
     * Possibile values
     * - list
     * - icon
     * - grid
     *
     * @var array $defaultListViews This set the default order for the views in lists
     */
    public $defaultListViews = ['list', 'grid'];
    
    /**
     * @var bool $enable_foreground
     */
    public $enable_foreground = false;
    
    /**
     * @var string $foreground_permission
     */
    public $foreground_permission = 'DISCUSSION_FOREGROUD_PERMISSION';
    
    /**
     * @var string $defaultWidgetIndexUrl
     */
    public $defaultWidgetIndexUrl = '/discussioni/discussioni-topic/own-interest-discussions';
    
    /**
     * @var bool $site_publish_enabled
     */
    public $site_publish_enabled = false;
    
    /*
     * @var bool disableStandardWorkflow Disable standard worflow, direct publish
     */
    public $disableStandardWorkflow = false;
    
    /**
     * @var bool $disableReportFlag
     */
    public $disableReportFlag = false;
    
    /**
     * hide block on _form relative to seo module even if it is present
     * @var bool $hideSeoModule
     */
    public $hideSeoModule = false;
    
    /**
     * @var bool $disableBefeControllerRules Enable this property to disable the BEFE rules in controller behaviors.
     */
    public $disableBefeControllerRules = false;
    
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            //MZ: commentato perchè mai eseguito non esisteva l'implementazione dell'interffaccia di bootstrap!
            // se dovesse servire va testato con il contesto giusto - ora in console quesa roba non funziona!
//            $this->controllerNamespace = 'open20\amos\discussioni\commands\controllers';
//            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/commands/controllers', __DIR__ . '/commands/controllers');
//            \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'commands' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        } else {
            $app->getUrlManager()->addRules([
                'discussioni/discussioni-topic/partecipa/<id:\d+>/<title:.*?>' => 'discussioni/discussioni-topic/partecipa',
            ], false);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if (\Yii::$app instanceof Application) {
            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/commands', __DIR__ . '/commands/');
            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
            //aggiunge le configurazioni trovate nel file config/config.php
            // Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
            $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
            \Yii::configure($this, ArrayHelper::merge($config, $this));
        } else {
            \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
            //aggiunge le configurazioni trovate nel file config/config.php
            // Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
            $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
            \Yii::configure($this, ArrayHelper::merge($config, $this));
        }
    }
    
    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "discussioni";
    }
    
    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\DiscussioniTopicSearch';
    }
    
    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\DiscussioniTopic';
    }
    
    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [
            WidgetGraphicsUltimeDiscussioni::className(),
            WidgetGraphicsDiscussioniInEvidenza::className(),
        ];
    }
    
    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public static function beginCreateNewSessionKey()
    {
        return 'beginCreateNewUrl_' . self::getModuleName();
    }
    
    /**
     * @inheritdoc
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = parent::getFrontEndMenu($dept);
        $app = \Yii::$app;
        if (
            is_null($app->user)
            || $app->user->id == $app->params['platformConfigurations']['guestUserId']
        ) {
            $menu .= $this->addFrontEndMenu(
                AmosDiscussioni::t('amosdiscussioni', '#menu_front_discussioni'),
                AmosDiscussioni::toUrlModule('/discussioni-topic/all-discussions')
            );
        } else {
            $menu .= $this->addFrontEndMenu(
                AmosDiscussioni::t('amosdiscussioni', '#menu_front_discussioni'),
                AmosDiscussioni::toUrlModule('/discussioni-topic/all-discussions')
            );
        }
        
        return $menu;
    }
    
    /**
     * @inheritdoc
     */
    public function getIndexActions()
    {
        return [
            'discussioni-topic/index',
            'discussioni-topic/all-discussions',
            'discussioni-topic/created-by',
            'discussioni-topic/admin-all-discussions',
            'discussioni-topic/to-validate-discussions',
            'discussioni-topic/own-interest-discussions'
        ];
    }
    
    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'discussioni-topic' => '/discussioni/discussioni-topic/own-interest-discussions',
        ];
    }
    
    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'discussioni-topic' => '/discussioni/discussioni-topic/all-discussions',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getControllerNames()
    {
        $names = [
            'discussioni-topic' => self::t('amosdiscussioni', "Discussioni"),
        ];
        return $names;
    }
}
