<?php
namespace frontend\modules\FLcommands;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\commands
 * @category   CategoryName
 */

use open20\amos\core\module\AmosModule;

class Module extends AmosModule {

    public $controllerNamespace = 'frontend\modules\FLcommands\commands';

    public function init(){
        parent::init();
    }

    /**
     * @param \yii\console\Application $app
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'frontend\modules\FLcommands\controllers';
        }
    }

    public function getWidgetIcons()
    {
        // TODO: Implement getWidgetIcons() method.
    }

    public function getDefaultModels()
    {
        // TODO: Implement getDefaultModels() method.
    }

    public function getWidgetGraphics()
    {
        // TODO: Implement getWidgetGraphics() method.
    }

    public static function getModuleName(){
        return 'FLcommands';
    }
}