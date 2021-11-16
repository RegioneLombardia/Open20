<?php

use backend\modules\statistics\widgets\icons\WidgetDataAnalysis;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m201118_180000_data_analysis_widget */
class m201118_180000_data_analysis_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'statistics';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetDataAnalysis::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 25,
                'child_of' =>  null,
            ]
        ];
    }
}
