<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200618_102213_add_order_to_event_type extends Migration
{
    const TABLE_EVENT_TYPE = "event_type";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_TYPE, 'order', $this->integer()->after('event_context_id'));


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_TYPE, 'order');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
