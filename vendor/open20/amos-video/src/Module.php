<?php

namespace open20\video;


use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\viedo\models\Video;
use open20\video\search\VideoSearch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * formazione module definition class
 * ####################### require amos-site-management test/1.6.0/1 for EmbeddedUtility
 */

class Module extends AmosModule implements SearchModuleInterface, CmsModuleInterface, ModuleInterface {
    /**
     * {@inheritdoc}
     */

    
    public static $CONFIG_FOLDER = 'config';

    const MAX_VIDEO_ON_DASHBOARD = 1;

    const ENABLED_ENV_TYPE = [
        'COMMUNITY' => 'COMMUNITY',
        'PLATFORM' => 'PLATFORM',
        'ALL' => 'ALL'
    ];
    public $enabledEnvironment = self::ENABLED_ENV_TYPE['COMMUNITY'];


    protected function getDefaultModels() {
        return [
            'Video' => __NAMESPACE__ . '\\' . 'models\Video',
            'VideoSearch' => __NAMESPACE__ . '\\' . 'models\search\VideoSearch',
        ];
    }

    /**
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'video';
    }

     public function getWidgetIcons() {
        return [];
    }

    public function getWidgetGraphics() {
        return [];
    }
    
    
    public function init() {
        parent::init();

        \Yii::setAlias('@open20/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');
        //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        Yii::configure($this, ArrayHelper::merge($config, $this));
    }
    
    

    /**
     * @return array
     
    public function getIndexActions()
    {
        return [
            '/video/video/index',
            '/video/video/all'
        ];
    }

    /**
     * @return array
     
    public function defaultControllerIndexRoute()
    {
        return [
            'video' => '/video/video/index',
        ];
    }

    /**
     * @return array
     

    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'video' => '/video/video/index',
        ];
    }

    /**
     * @return array
     
    public function getControllerNames()
    {
        $names = [
            'video' => self::t('video', "Video"),
        ];

        return $names;
    }


    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return  VideoSearch::class;
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return Video::class;
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        return 'feed';
    }
}
