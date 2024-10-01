<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m181107_151031_create_widget_management  extends \open20\amos\core\migration\AmosMigrationWidgets
{
    const MODULE_NAME = 'dashboard';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\dashboard\widgets\icons\WidgetIconManagement::className(),
                'type' => \open20\amos\dashboard\models\AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'dashboard_visible' => 1,
                'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED
            ]
        ];
    }
}
