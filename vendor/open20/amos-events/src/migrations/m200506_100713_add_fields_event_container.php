<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200506_100713_add_fields_event_container extends Migration
{
    const TABLE_EVENT_CO= "event_container";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_CO, 'event_group_referent_id', $this->integer()->after('id'));
     $this->addForeignKey('fk_event_container_group_referent_id1', self::TABLE_EVENT_CO, 'event_group_referent_id', 'event_group_referent', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_event_container_group_referent_id1', self::TABLE_EVENT_CO);
        $this->dropColumn(self::TABLE_EVENT_CO, 'event_group_referent_id');


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
