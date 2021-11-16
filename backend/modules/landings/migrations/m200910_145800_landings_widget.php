<?php

use backend\modules\campains\widgets\icons\WidgetCampains;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m200820_110000_campains_widget */
class m200910_145800_landings_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'landings';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \backend\modules\landings\widgets\icons\WidgetIconPreferenceLandings::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 10,
                'child_of' =>  null,
            ]
        ];
    }
}
