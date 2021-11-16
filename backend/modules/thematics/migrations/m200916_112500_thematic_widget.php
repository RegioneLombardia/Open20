<?php


use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m200916_112500_thematic_widget */
class m200916_112500_thematic_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'thematics';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \backend\modules\thematics\widgets\icons\WidgetThematic::className(),
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
