<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m190118_103227_widgets_service_mail*/
class m190118_103227_widgets_service_mail extends AmosMigrationWidgets
{
    const MODULE_NAME = 'newsletter';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \amos\newsletter\widgets\icons\WidgetIconNewsletterDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 1,
            ],
            [
                'classname' => \amos\newsletter\widgets\icons\WidgetIconServiceMail::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 1,
                'child_of' => \amos\newsletter\widgets\icons\WidgetIconNewsletterDashboard::className()
            ],      [
                'classname' => \amos\newsletter\widgets\icons\WidgetIconConfigurationFields::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 2,
                'child_of' => \amos\newsletter\widgets\icons\WidgetIconNewsletterDashboard::className()
            ],

        ];
    }
}
