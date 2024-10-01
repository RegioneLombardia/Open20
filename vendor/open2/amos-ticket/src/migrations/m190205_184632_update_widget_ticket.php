<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigration;
use open20\amos\dashboard\models\AmosWidgets;
use open2\amos\ticket\widgets\icons\WidgetIconTicketDashboard;

/**
 * Class m190205_184632_update_widget_ticket
 */
class m190205_184632_update_widget_ticket extends AmosMigration
{
    const MODULE_NAME = 'ticket';
    const COMMUNITY_MODULE_NAME = 'community';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('amos_widgets', [
            'classname' => WidgetIconTicketDashboard::className(),
            'type' => AmosWidgets::TYPE_ICON,
            'module' => self::COMMUNITY_MODULE_NAME,
            'status' => AmosWidgets::STATUS_ENABLED,
            'default_order' => 150,
            'sub_dashboard' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s'),
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('amos_widgets', [
            'classname' => WidgetIconTicketDashboard::className(),
            'type' => AmosWidgets::TYPE_ICON,
            'module' => self::COMMUNITY_MODULE_NAME,
            'status' => AmosWidgets::STATUS_ENABLED,
            'default_order' => 150,
            'sub_dashboard' => 1,
        ]);
        return true;
    }
}
