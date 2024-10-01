<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\organizational_unit
 * @category   CategoryName
 */

namespace open20\agid\dataset;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use yii\helpers\ArrayHelper;


/**
 * Class Module
 * @package open20\dataset
 */
class Module extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface
{

    public static $CONFIG_FOLDER = 'config';

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'dataset';
    }

    public function getWidgetIcons()
    {
        return [];
    }

    public function getWidgetGraphics()
    {
        return [];
    }

    /**
     * Get default model classes
     */
    protected function getDefaultModels()
    {
        return [
            'AgidDataset' => __NAMESPACE__.'\\'.'models\AgidDataset',
            'AgidDatasetSearch' => __NAMESPACE__.'\\'.'models\search\AgidDatasetSearch',
        ];
    }

    public static function getModelClassName() {
        return Module::instance()->model('AgidDataset');
    }

    public static function getModelSearchClassName() {
        return Module::instance()->model('AgidDatasetSearch');
    }

    public static function getModuleIconName() {
        return null;
    }
    
    
    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = parent::getFrontEndMenu();
        $app  = \Yii::$app;
        if (!$app->user->isGuest && (\Yii::$app->user->can('AGIDDATASET_READ')||\Yii::$app->user->can('REDACTOR_DATASET'))) {
            $menu .= $this->addFrontEndMenu(Module::t('amosdataset','#menu_front_dataset'), Module::toUrlModule('/agid-dataset/index'),$dept);
        }
        return $menu;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, $this));
    }

}
