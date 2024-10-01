<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m200624_183920_add_amos_widgets_widget_icon_moodle_dashboard*/
class m200624_183920_add_amos_widgets_widget_icon_moodle_dashboard extends AmosMigrationWidgets
{
    const MODULE_NAME = 'moodle';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                
            ]
        ];
    }
}
