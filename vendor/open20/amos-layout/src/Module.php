<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace open20\amos\layout;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\layout\components\Layout;
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
    public $controllerNamespace = 'open20\amos\layout\controllers';

    /**
     * View path of maintenance page
     * @var string $viewMaintenanceMode
     */
    public $viewMaintenanceMode = '@vendor/open20/amos-layout/src/views/maintenance/maintenance';

    /**
     * Choose to display a single logout action in the navbar or multiple ones
     * @var boolean $advancedLogoutActions
     */
    public $advancedLogoutActions = false;

    public $breadcrumbsIconHomeText = false;

    public $excludeNetworkView = ['amos\planner\models\PlanWork'];

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
        //Set Layout
        \Yii::$app->set('layout', Layout::className());
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'layout';
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
