<?php

use amos\chrono\widgets\icons\WidgetIconChronoTasks;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

class m200410_105642_add_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'chrono';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconChronoTasks::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ],
        ];
    }
}