<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200708_102213_add_patronage_event_type extends Migration
{
    const TABLE_EVENT_TYPE = "event_type";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_TYPE, 'patronage', $this->integer(1)->defaultValue(0)->after('event_context_id'));


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_TYPE, 'patronage');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
