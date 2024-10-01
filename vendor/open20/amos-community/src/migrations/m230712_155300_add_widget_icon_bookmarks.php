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

class m230712_155300_add_widget_icon_bookmarks extends \open20\amos\core\migration\AmosMigrationWidgets
{
    const MODULE_NAME = 'community';

    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\community\widgets\icons\WidgetIconBookmarks::class,
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'sub_dashboard' => 1,
                'dashboard_visible' => 0,
                'default_order' => 40
            ]
        ];
    }
}
