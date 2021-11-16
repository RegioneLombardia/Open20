<?php

namespace backend\modules\userimporter;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use Yii;

class Module extends AmosModule implements ModuleInterface, \yii\base\BootstrapInterface
{

    public $whiteListFilesExtensions = 'csv, xls, xlsx';
    public $url_example_file;
    public $importOptin = true;

    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    public $config = [];


    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'backend\modules\userimporter\commands';
        }
    }

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "userimporter";
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // initialize the module with the configuration loaded from config.php
        $config = require(__DIR__.DIRECTORY_SEPARATOR.self::$CONFIG_FOLDER.DIRECTORY_SEPARATOR.'config.php');

        Yii::configure($this, $config);
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
        return [
        ];
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