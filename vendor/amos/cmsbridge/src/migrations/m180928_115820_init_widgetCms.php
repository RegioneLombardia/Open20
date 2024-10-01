<?php

use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\core\migration\AmosMigrationWidgets;

class m180928_115820_init_widgetCms extends AmosMigrationWidgets {

    const MODULE_NAME = 'cmsbridge';

    protected $widgets;

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs() {
        $this->widgets = [
            [
                'classname' => \amos\cmsbridge\widgets\icons\WidgetIconCmsDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ]
        ];
    }
}
