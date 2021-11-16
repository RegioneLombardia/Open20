<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m170829_145506_change_widget_chat_dashboard_visible
 */
class m170829_145506_change_widget_chat_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'chat';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\chat\widgets\icons\WidgetIconChat::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
