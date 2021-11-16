<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m201101_153313_create_table_event_push_notifications extends Migration
{
    const TABLE = "event_push_notification";
    const TABLE_SENT = "event_push_notification_sent";


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
                'type' => $this->text()->comment('Type'),
                'content_class' => $this->string()->comment('Class'),
                'content_id' => $this->integer()->comment('Content id'),
                'status' => $this->string()->comment('Status'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

            $this->addForeignKey('fk_event_push_notify_event_id_id1','event_push_notification', 'event_id', 'event', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


        if ($this->db->schema->getTableSchema(self::TABLE_SENT, true) === null)
        {
            $this->createTable(self::TABLE_SENT, [
                'id' => Schema::TYPE_PK,
                'event_push_notification_id' => $this->integer()->comment('Push notification'),
                'user_id' => $this->integer()->comment('User'),
                'sent_at' => $this->dateTime()->comment('Sent at'),
                'status' => $this->string()->comment('Status'),
                'created_at' =>  $this->integer()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

            $this->addForeignKey('fk_event_push_notification_id_event_push_id_id1','event_push_notification_sent', 'event_push_notification_id', 'event_push_notification', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        $this->addColumn('event_internal_list','active_query', $this->text()->defaultValue(null)->after('query_sql'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropTable(self::TABLE);
        $this->dropTable(self::TABLE_SENT);
        $this->dropColumn('event_internal_list','active_query');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
