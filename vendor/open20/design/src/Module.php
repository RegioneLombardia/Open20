<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace open20\design;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\design\components\LayoutManager;
use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Module
 * @package open20\amos\socialauth
 */
class Module extends AmosModule implements ModuleInterface, BootstrapInterface
{
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\design\controllers';
    
    /**
     *  true if must check user permission in CMS tables.
     * @var boolean
     */
    public $checkCmsPermissions = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->defineModelClasses();
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Yii::$app->set('layoutmanager', LayoutManager::className());
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'design';
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
}
