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
use open20\amos\events\widgets\icons\WidgetIconEvents;
use open20\amos\events\widgets\icons\WidgetIconSubscribedEvents;

/**
 * Class m200609_143012_create_widget_icon_subscribed_events
 */
class m210408_175112_create_widget_icon_event_cnfigurations extends AmosMigrationWidgets
{
    const MODULE_NAME = 'events';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEventConfigurations::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'default_order' => 25,
                'dashboard_visible' => 1
            ]
        ];
    }
}
