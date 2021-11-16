<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200324_122013_create_table_event_invitation_sent extends Migration
{
    const TABLE = "event_invitation_sent";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'event_internal_list_id' => $this->integer()->comment('Internal list'),
                'user_id' => $this->integer()->comment('User'),
                'email' => $this->string()->comment('Email'),
                'send_at' => $this->dateTime()->comment('Send at'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
        $this->addForeignKey('fk_event_invitation_sent_event_int_list_id1',self::TABLE, 'event_internal_list_id', 'event_internal_list', 'id');
        $this->addForeignKey('fk_event_invitation_sent_user_id1',self::TABLE, 'user_id', 'user', 'id');


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropForeignKey('fk_event_email_templates_event_id1', self::TABLE);
        $this->dropTable(self::TABLE);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
