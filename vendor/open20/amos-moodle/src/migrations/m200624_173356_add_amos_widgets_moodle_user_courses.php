<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m200624_173356_add_amos_widgets_moodle_user_courses*/
class m200624_173356_add_amos_widgets_moodle_user_courses extends AmosMigrationWidgets
{
    const MODULE_NAME = 'moodle';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleUserCourses::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'child_of' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleDashboard::className(),
            ]
        ];
    }
}
