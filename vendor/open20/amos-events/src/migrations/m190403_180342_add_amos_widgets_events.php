<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m190403_180342_add_amos_widgets_events*/
class m190403_180342_add_amos_widgets_events extends AmosMigrationWidgets
{
    const MODULE_NAME = 'events';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\events\widgets\graphics\WidgetGraphicsEvents::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC ,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                
            ]
        ];
    }
}
