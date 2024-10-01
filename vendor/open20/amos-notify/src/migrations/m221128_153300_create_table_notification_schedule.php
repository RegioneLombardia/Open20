<?php

use yii\db\Migration;

class m221128_153300_create_table_notification_schedule extends Migration
{
    const TABLE = 'notification_schedule';
    const TABLE_CONTENT = 'notification_schedule_content';

    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'status' => $this->string()->defaultValue(null)->comment('Status'),
                'type' => $this->integer()->defaultValue(null)->comment('Type'),
                'last_notified_user_id' => $this->integer()->defaultValue(0)->comment('Content classname'),
                'ended_at' => $this->dateTime()->comment('Ended at'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);


        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_CONTENT, true) === null) {
            $this->createTable(self::TABLE_CONTENT, [
                'id' => $this->primaryKey(),
                'notification_schedule_id' => $this->integer()->defaultValue(null)->comment('Schedule'),
                'notification_id' => $this->integer()->defaultValue(null)->comment('Notification'),
                'classname' => $this->string()->defaultValue(null)->comment('Classname'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' => $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' => $this->integer()->comment('Created by'),
                'updated_by' => $this->integer()->comment('Updated at'),
                'deleted_by' => $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);


        $this->addForeignKey('fk_notification_schedule_content_notify_schedule_id', self::TABLE_CONTENT, 'notification_schedule_id', self::TABLE, 'id');
        $this->addForeignKey('fk_notification_schedule_content_notification_id', self::TABLE_CONTENT, 'notification_id', 'notification', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
//        $this->addForeignKey('fk_notification_schedule_last_user_notified', self::TABLE, 'last_notified_user_id', 'user', 'id');

    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE);
        $this->dropTable(self::TABLE_CONTENT);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
