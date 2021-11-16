<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200319_102013_create_table_event_email_templates extends Migration
{
    const TABLE = "event_email_templates";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'event_id' => $this->integer()->comment('Event'),
                'save_the_date' => $this->text()->comment('Save the date'),
                'save_the_date_subject' => $this->text()->comment('Save the date subject'),
                'registration_email' => $this->text()->comment('Registration email'),
                'registration_email_subject' => $this->text()->comment('Registration email subject'),
                'confirm_registration' => $this->text()->comment('Confirm registration'),
                'confirm_registration_subject' => $this->text()->comment('Confirm registration subject'),
                'info_waiting_list' => $this->text()->comment('Info waiting list'),
                'info_waiting_list_subject' => $this->text()->comment('Info waiting list subject'),
                'send_tickets' => $this->text()->comment('Send tickets'),
                'send_tickets_subject' => $this->text()->comment('Send tickets subject'),
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
        $this->addForeignKey('fk_event_email_templates_event_id1',self::TABLE, 'event_id', 'event', 'id');


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
