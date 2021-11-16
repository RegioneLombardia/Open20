<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m210518_121500_add_fields_import_task extends Migration
{
    const TABLE_EVENT_LIST= "user_import_task";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_sending_id', $this->integer()->after('accept'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_import_id', $this->integer()->after('accept'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_message_id', $this->integer()->after('accept'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_group_id', $this->integer()->after('accept'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'mailup_list_id', $this->integer()->after('accept'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'n_user_found', $this->integer()->after('accept'));
     $this->addColumn(self::TABLE_EVENT_LIST, 'n_sent', $this->integer()->after('accept'));
     $this->alterColumn(self::TABLE_EVENT_LIST, 'status', $this->string());

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
        $this->dropColumn(self::TABLE_EVENT_LIST, 'n_user_found');
        $this->dropColumn(self::TABLE_EVENT_LIST, 'n_sent');
        $this->alterColumn(self::TABLE_EVENT_LIST, 'status', $this->integer());



        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
