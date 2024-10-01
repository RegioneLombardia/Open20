<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\dashboard\models\AmosWidgets;

class m230707_175300_add_widget_video_to_community extends \open20\amos\core\migration\AmosMigrationWidgets
{
    const MODULE_NAME = 'community';

    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\video\widgets\graphics\WidgetGraphicVideo::class,
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'sub_dashboard' => 1,
                'dashboard_visible' => 1,
                'default_order' => 15
            ],
            [
                'classname' => \open20\video\widgets\icons\WidgetIconVideo::class,
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'sub_dashboard' => 1,
                'dashboard_visible' => 1,
                'default_order' => 15
            ]
        ];
    }
}
