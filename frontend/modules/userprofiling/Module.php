<?php
namespace frontend\modules\userprofiling;

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

    public $controllerNamespace = 'frontend\modules\userprofiling\controllers';

    public function init(){
        parent::init();
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
        return 'userprofiling';
    }
}