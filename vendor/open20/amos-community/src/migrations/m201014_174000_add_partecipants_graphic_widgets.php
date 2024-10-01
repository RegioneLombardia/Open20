<?php

use open20\amos\community\widgets\graphics\WidgetGraphicsCommunityPartecipants;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m201009_174000_add_slider_graphic_widgets */
class m201014_174000_add_partecipants_graphic_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'community';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetGraphicsCommunityPartecipants::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 1,
            ],
            
           
        ];
    }
}
