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
 * Class m170904_130136_change_events_dashboard_widget_child_of_null
 */
class m170904_130136_change_events_dashboard_widget_child_of_null extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'child_of' => null,
                'update' => true
            ]
        ];
    }
}
