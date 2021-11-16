<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200401_180713_create_table_event_communication extends Migration
{
    const TABLE = "event_communication";
    const TABLE_SENT = "event_communication_sent";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'event_id' => $this->integer()->comment('event'),
                'title' => $this->string()->comment('Title'),
                'communication_type' => $this->integer()->comment('n user'),
                'n_users' => $this->integer()->comment('n user'),
                'text_email' => $this->text()->comment('Text'),
                'subject_email' => $this->text()->comment('Subject'),
                'status' => $this->string()->comment('Status'),
                'sent_at' => $this->dateTime()->comment('Send at'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_event_communication_event_id1',self::TABLE, 'event_id', 'event', 'id');

        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_SENT, true) === null)
        {
            $this->createTable(self::TABLE_SENT, [
                'id' => Schema::TYPE_PK,
                'event_communication_id' => $this->integer()->comment('Internal list'),
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
            $this->addForeignKey('fk_event_communication_sent_event_comm_id1',self::TABLE_SENT, 'event_communication_id', 'event_communication', 'id');
            $this->addForeignKey('fk_event_communication_sent_user_id1',self::TABLE_SENT, 'user_id', 'user', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_event_communication_event_id1',self::TABLE);
        $this->dropForeignKey('fk_event_communication_sent_event_comm_id1',self::TABLE_SENT);
        $this->dropForeignKey('fk_event_communication_sent_user_id1',self::TABLE_SENT);
        $this->dropTable(self::TABLE);
        $this->dropTable(self::TABLE_SENT);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
