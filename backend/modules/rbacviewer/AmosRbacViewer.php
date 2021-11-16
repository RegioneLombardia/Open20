<?php
namespace backend\modules\rbacviewer;

use mdm\admin\Module;
use open20\amos\core\module\ModuleInterface;

/**
 * Class Module
 * @package backend\modules\rbacviewer
 */
class AmosRbacViewer extends Module implements ModuleInterface
{

    /**
     * Get default model classes
     */
    protected function getDefaultModels()
    {
        return [];
    }

    public function getWidgetIcons()
    {
        return [];
    }

    public function getWidgetGraphics()
    {
        return [];
    }

    public static function getModuleName()
    {
        return 'rbacviewer';
    }
}