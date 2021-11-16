<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni;

use open20\amos\core\module\AmosModule;
use open20\amos\comuni\widgets\icons\WidgetIconAmmComuni;
use open20\amos\comuni\widgets\icons\WidgetIconComuni;
use open20\amos\comuni\widgets\icons\WidgetIconProvince;

/**
 * AmosComuni module definition class
 */
class AmosComuni extends AmosModule {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\comuni\controllers';
    public $newFileMode = 0666;
    public $newDirMode = 0777;
    public $name = 'comuni';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    protected function getDefaultModels() {
        return [];
    }

    /**
     *
     * @return string
     */
    public static function getModuleName() {
        return 'comuni';
    }

    public function getWidgetGraphics() {
        return NULL;
    }

    public function getWidgetIcons() {
        return [
            WidgetIconAmmComuni::className(),
            WidgetIconComuni::className(),
            WidgetIconProvince::className(),
        ];
    }

}
