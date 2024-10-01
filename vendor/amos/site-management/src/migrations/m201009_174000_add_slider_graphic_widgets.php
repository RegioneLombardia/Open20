<?php

use amos\sitemanagement\widgets\graphics\WidgetCommunityImagesSlider;
use amos\sitemanagement\widgets\graphics\WidgetCommunityVideosSlider;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m201009_174000_add_slider_graphic_widgets */
class m201009_174000_add_slider_graphic_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'organizzazioni';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetCommunityImagesSlider::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 1,
            ],
            [
                'classname' => WidgetCommunityVideosSlider::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 2,
            ],
           
        ];
    }
}
