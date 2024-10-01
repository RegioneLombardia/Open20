<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\events\widgets\icons\WidgetIconEventRooms;
use open20\amos\events\widgets\icons\WidgetIconEvents;

/**
 * Class m200420_171812_add_event_room_widget
 */
class m200420_171812_add_event_room_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'events';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconEventRooms::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => WidgetIconEvents::className(),
                'default_order' => 60,
                'dashboard_visible' => 0
            ]
        ];
    }
}
