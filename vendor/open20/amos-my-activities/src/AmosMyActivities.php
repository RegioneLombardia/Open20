<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities
 * @category   CategoryName
 */

namespace open20\amos\myactivities;

use open20\amos\core\module\AmosModule;
use open20\amos\myactivities\widgets\icons\WidgetIconMyActivities;

/**
 * Class AmosMyActivities
 * @package open20\amos\myactivities
 */
class AmosMyActivities extends AmosModule
{
    public $controllerNamespace = 'open20\amos\myactivities\controllers';
    public $name = 'MYACTIVITIES';
    
    public $orderType = SORT_DESC;


    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'myactivities';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {

    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            WidgetIconMyActivities::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [
            'MyActivities' => __NAMESPACE__ . '\\' . 'models\MyActivities',
            'MyActivitiesModelSearch' => __NAMESPACE__ . '\\' . 'models\search\MyActivitiesModelSearch',
        ];
    }
}
