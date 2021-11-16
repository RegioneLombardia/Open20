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
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m180917_170759_add_widget_chat_assistance
 */
class m180917_170759_add_widget_chat_assistance extends AmosMigrationWidgets
{

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\chat\widgets\icons\WidgetIconChatAssistance::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'chat',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'default_order' => 55,
                'dashboard_visible' => 0,
            ]
        ];
    }
}
