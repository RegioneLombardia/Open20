<?php

namespace open20\amos\utility;

use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\module\AmosModule;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package open20\amos\utility
 */
class Module extends AmosModule implements BreadcrumbInterface {

    /**
     *
     * @var string $controllerNamespace Controller namespace
     */
    public $controllerNamespace = 'open20\amos\utility\controllers';

    /**
     * 
     * @var bool
     */
    public $disableDeleteFrontendAssets = false;

    /**
     * 
     * @var bool
     */
    public $disableDeleteBackendAssets = false;

    /**
     * @inheritdoc
     */
    public function init() {
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
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons() {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics() {
        return [];
    }

    /**
     * @return string
     */
    public static function getModuleName() {
        return 'utility';
    }

    /**
     * Get default models
     * @return array
     */
    protected function getDefaultModels() {
        return [];
    }

    /**
     * @param $app
     */
    public function bootstrap($app) {
        $this->setControllerNameSpace($app);
    }

    /**
     * @param \yii\console\Application $app
     */
    private function setControllerNameSpace($app) {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'open20\amos\utility\commands';
        }
    }

    /**
     * @return array
     */
    public function getIndexActions() {
        return [
            'export-luya/index',
            'export-luya/export-page',
            'platform-configs/index',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute() {
        return [
            'export-luya' => '/utility/export-luya/index',
            'platform-configs' => '/utility/platform-configs/index',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged() {
        return [
            'export-luya' => '/utility/export-luya/index',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames() {
        $names = [
            'export-luya' => self::t('amosnews', "Gestione pagine cms"),
            'platform-configs' => self::t('amosnews', "Configurazioni piattaforma"),
        ];

        return $names;
    }

}
