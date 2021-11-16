<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180327_162827_add_amos_widgets_een_archived*/
class m181112_123027_add_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'sitemanagement';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementAdministration::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 40,
                'child_of' =>  \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
            ],
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementContainerFather::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 20,
                'child_of' =>  \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
            ],
//            [
//                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementLandingFather::className(),
//                'type' => AmosWidgets::TYPE_ICON,
//                'module' => self::MODULE_NAME,
//                'status' => AmosWidgets::STATUS_ENABLED,
//                'dashboard_visible' => 0,
//                'default_order' => 30,
//                'child_of' =>  \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
//            ]
        ];
    }
}
