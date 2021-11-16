<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200515_095513_add_fields_event_container extends Migration
{
    const TABLE_EVENT_LIST= "event_container";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_LIST, 'end_date', $this->dateTime()->after('description'));
        $this->addColumn(self::TABLE_EVENT_LIST, 'begin_date', $this->dateTime()->after('description'));

        $this->addColumn('event', 'publish_on_prl', $this->integer()->defaultValue(0)->after('event_container_order'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'end_date');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'begin_date');
        $this->dropColumn('event', 'publish_on_prl');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
