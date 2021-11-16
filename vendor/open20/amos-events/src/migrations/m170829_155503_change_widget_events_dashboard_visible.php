<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m170829_155503_change_widget_events_dashboard_visible
 */
class m170829_155503_change_widget_events_dashboard_visible extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
