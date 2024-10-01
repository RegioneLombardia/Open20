<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace open20\gamification;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\models\Gamification;
use open20\models\search\GamificationSearch;

/**
 * registry module definition class
 */
class Module extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface {
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\gamification\controllers';
    public $newFileMode = 0666;
    public $name = 'gamification';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));

    }

    protected function getDefaultModels()
    {
        return [];
    }

    /**
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'gamification';
    }

    public function getWidgetGraphics()
    {
        return NULL;
    }

    public function getWidgetIcons()
    {
        return [

        ];
    }

    /**
     * @return array
     */
    public function getIndexActions()
    {
        return [
            'gamification/index',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'gamification' => '/gamification/gamification/index',

        ];
    }

    /**
     * @return array
    */
    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'gamification' => '/gamification/gamification/index',
        ];
    }

    /**
     * @inheritdoc
     *
    public static function getModuleIconName()
    {
    return 'feed';
    }*/
    public function getControllerNames()
    {
        $names = [
            'gamification' => self::t('gamification', "Gamification"),

        ];

        return $names;
    }


    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return GamificationSearch::className();
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return Gamification::className();
    }

    public static function getModuleIconName()
    {
        return 'feed';
    }


}
