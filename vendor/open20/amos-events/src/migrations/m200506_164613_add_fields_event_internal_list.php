<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200506_164613_add_fields_event_internal_list extends Migration
{
    const TABLE_EVENT_LIST= "event_internal_list";
    const TABLE_EVENT_COM= "event_communication";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_LIST, 'n_sent', $this->integer()->defaultValue(0)->after('n_user_found'));
     $this->addColumn(self::TABLE_EVENT_COM, 'n_sent', $this->integer()->defaultValue(0)->after('n_users'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'n_sent');
        $this->dropColumn(self::TABLE_EVENT_COM, 'n_sent');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
