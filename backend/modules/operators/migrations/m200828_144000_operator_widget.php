<?php

use backend\modules\operators\widgets\icons\WidgetOperators;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m200828_144000_operator_widget */
class m200828_144000_operator_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'operators';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetOperators::className(),
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
