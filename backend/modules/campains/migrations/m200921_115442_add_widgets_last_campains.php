<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m200921_115442_add_widgets_last_campains*/
class m200921_115442_add_widgets_last_campains extends AmosMigrationWidgets
{
    const MODULE_NAME = 'campains';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \backend\modules\campains\widgets\graphics\WidgetGraphicsLastCampains::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 1,
            ],

        ];
    }
}
