<?php

/**
 * Aria S.p.A.
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
    
    /**
     * 
     * @var type
     */
    public $newFileMode = 0666;
    
    /**
     * 
     * @var type
     */
    public $newDirMode = 0777;
    
    /**
     * 
     * @var type
     */
    public $name = 'comuni';

    /**
     * 
     */
    public $selectOnlyTheseNations = [];
    
    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * 
     * @return type
     */
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

    /**
     * 
     * @return type
     */
    public function getWidgetGraphics() {
        return null;
    }

    /**
     * 
     * @return type
     */
    public function getWidgetIcons() {
        return [
            WidgetIconAmmComuni::class,
            WidgetIconComuni::class,
            WidgetIconProvince::class,
        ];
    }

}
