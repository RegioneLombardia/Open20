<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m201002_173211_add_sliders_widgets */
class m201002_173211_add_sliders_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'sitemanagement';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSMCommunitySliderElementsImages::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'organizzazioni',
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'sub_dashboard' => 0,
                'default_order' => 100,
                'child_of' =>  null,
            ],
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSMCommunitySliderElementsVideos::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'organizzazioni',
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'sub_dashboard' => 0,
                'default_order' => 110,
                'child_of' =>  null,
            ]
        ];
    }
}
