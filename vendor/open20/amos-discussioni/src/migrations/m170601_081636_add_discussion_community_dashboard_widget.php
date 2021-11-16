<?php

use open20\amos\dashboard\models\AmosWidgets;

class m170601_081636_add_discussion_community_dashboard_widget extends \open20\amos\core\migration\AmosMigrationWidgets
{
    const MODULE_NAME = 'discussioni';

    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioni::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'update' => true,
                'status' => AmosWidgets::STATUS_DISABLED
            ],

        ];
    }
}