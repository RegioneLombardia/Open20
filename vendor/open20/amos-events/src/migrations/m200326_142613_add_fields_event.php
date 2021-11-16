<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200326_142613_add_fields_event extends Migration
{
    const TABLE_EVENT = "event";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT, 'event_group_referent_id', $this->integer()->defaultValue(null)->after('description')->comment('Group'));
        $this->addForeignKey('fk_event_event_group_referent_id','event', 'event_group_referent_id','event_group_referent', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_event_event_group_referent_id','event');
        $this->dropColumn(self::TABLE_EVENT, 'event_group_referent_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
