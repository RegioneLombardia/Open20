<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180327_162827_add_amos_widgets_een_archived*/
class m180904_103227_add_widgets_landing extends AmosMigrationWidgets
{
    const MODULE_NAME = 'sitemanagement';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLanding::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 21,
                'child_of' =>  \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
            ],
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingPubblication::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 22,
                'child_of' =>  \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
            ]
        ];
    }
}
