<?php

use backend\modules\userimporter\widgets\icons\WidgetIconImporter;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


class m210518_100942_add_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'userimporter';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconImporter::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 70
            ],
        ];
    }
}