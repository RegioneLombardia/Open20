<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @category   CategoryName
 */

namespace backend\modules\statistics;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\layout\components\Layout;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package backend\modules\statistics
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
    public $controllerNamespace = 'backend\modules\statistics\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->defineModelClasses();
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, ["params" => $this->params]));
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
        return 'statistics';
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
