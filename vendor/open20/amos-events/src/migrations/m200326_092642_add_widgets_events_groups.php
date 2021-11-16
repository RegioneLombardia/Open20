<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m190403_180342_add_amos_widgets_events*/
class m200326_092642_add_widgets_events_groups extends AmosMigrationWidgets
{
    const MODULE_NAME = 'events';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconCreateNewEvent::className(),
                'type' => AmosWidgets::TYPE_ICON ,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ],
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEventOperators::className(),
                'type' => AmosWidgets::TYPE_ICON ,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ],
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEventDg::className(),
                'type' => AmosWidgets::TYPE_ICON ,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ],
        ];
    }
}
