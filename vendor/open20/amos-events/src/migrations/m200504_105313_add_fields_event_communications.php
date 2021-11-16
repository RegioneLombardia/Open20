<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200504_105313_add_fields_event_communications extends Migration
{
    const TABLE_EVENT_COMMUNICATION= "event_communication";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_sending_id', $this->integer()->after('n_users'));
     $this->addColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_import_id', $this->integer()->after('n_users'));
     $this->addColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_message_id', $this->integer()->after('n_users'));
     $this->addColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_group_id', $this->integer()->after('n_users'));
     $this->addColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_list_id', $this->integer()->after('n_users'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_sending_id');
        $this->dropColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_import_id');
        $this->dropColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_message_id');
        $this->dropColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_group_id');
        $this->dropColumn(self::TABLE_EVENT_COMMUNICATION, 'mailup_list_id');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
