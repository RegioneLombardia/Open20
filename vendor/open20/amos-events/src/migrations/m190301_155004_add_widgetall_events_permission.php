<?php

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


class m190301_155004_add_widgetall_events_permission extends AmosMigrationWidgets
{
    const MODULE_NAME = 'events';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconAllEvents::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'default_order' => 15
            ],
            
        ];
    }
}