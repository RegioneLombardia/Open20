<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace backend\modules\landings;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\layout\components\Layout;
use yii\base\BootstrapInterface;

/**
 * Class Module
 * @package backend\modules\operators
 */
class Module extends AmosModule implements ModuleInterface, BootstrapInterface,  SearchModuleInterface, CmsModuleInterface
{
    public static $CONFIG_FOLDER = 'config';
    public $newFileMode          = 0666;
    public $newDirMode           = 0777;

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\landings\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->defineModelClasses();
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));

    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        //Set Layout
        \Yii::$app->set('layout', Layout::className());
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'landings';
    }

    /**
     * @inheritdoc
     */
    public static function getModuleIconName()
    {
        return 'calendar';
    }


    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\PreferenceLandingSearch';
    }

    /**
     * @inheritdoc
     */
    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\PreferenceLanding';
    }
}
