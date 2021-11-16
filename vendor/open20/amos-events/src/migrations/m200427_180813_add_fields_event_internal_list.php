<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200427_180813_add_fields_event_internal_list extends Migration
{
    const TABLE_EVENT_LIST= "event_internal_list";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_sending_id', $this->integer()->after('n_user_found'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_import_id', $this->integer()->after('n_user_found'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_message_id', $this->integer()->after('n_user_found'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_group_id', $this->integer()->after('n_user_found'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_list_id', $this->integer()->after('n_user_found'));
     
     $this->addColumn(self::TABLE_EVENT_LIST, 'status', $this->string()->after('description'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'template', $this->string()->after('description'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'mailup_sending_id');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'mailup_import_id');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'mailup_message_id');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'mailup_group_id');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'mailup_list_id');

        $this->dropColumn(self::TABLE_EVENT_LIST, 'status');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'template');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
