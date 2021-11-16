<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `m200910_150000_create_table_preference_communication`.
 */
class m200910_150000_create_table_preference_communication extends Migration
{
    const TABLE_SENT = "preference_communication_sent";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_SENT, true) === null) {
            $this->createTable(self::TABLE_SENT, [
                'id' => Schema::TYPE_PK,
                'preference_campain_channel_mm_id' => $this->integer()->comment('Internal list'),
                'user_id' => $this->integer()->comment('User'),
                'email' => $this->string()->comment('Email'),
                'sent_at' => $this->dateTime()->comment('Send at'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_preference_communication_sent_channel_id1', self::TABLE_SENT, 'preference_campain_channel_mm_id', 'preference_campain_channel_mm', 'id');
            $this->addForeignKey('fk_preference_communication_sent_user_id1', self::TABLE_SENT, 'user_id', 'user', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_event_communication_sent_event_comm_id1', self::TABLE_SENT);
        $this->dropForeignKey('fk_event_communication_sent_user_id1', self::TABLE_SENT);
        $this->dropTable(self::TABLE_SENT);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
