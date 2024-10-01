<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
 * Class m171031_120002_add_amos_widget_variazioni_comuni*/
class m171207_100000_add_widget_videoconference extends AmosMigrationWidgets
{
    const MODULE_NAME = 'videoconference';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\videoconference\widgets\icons\WidgetIconVideoconference::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'dashboard_visible' => 1,
                //'child_of' => \open20\amos\videoconference\widgets\icons\WidgetIconVideoconference::className(),
            ]
        ];
    }
}