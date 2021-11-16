<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200515_150013_add_field_event_id_father extends Migration
{
    const TABLE_EVENT_LIST= "event";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_LIST, 'event_id', $this->integer()->defaultValue(0)->after('publish_on_prl'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'event_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
