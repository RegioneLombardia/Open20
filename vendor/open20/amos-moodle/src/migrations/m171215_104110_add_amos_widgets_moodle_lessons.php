<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m171215_104110_add_amos_widgets_moodle_lessons*/
class m171215_104110_add_amos_widgets_moodle_lessons extends AmosMigrationWidgets
{
    const MODULE_NAME = 'moodle';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleLessons::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1,
            ]
        ];
    }
}
