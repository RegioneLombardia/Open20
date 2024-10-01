<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200409_092013_create_table_chrono_task extends Migration
{
    const TABLE = "{{%chrono_task}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                    'id' => Schema::TYPE_PK,
                    'name' => $this->string()->comment('name'),
                    'description' => $this->string()->comment('description'),
                    'schedule' => $this->string()->comment('schedule'),
                    'command' => $this->string()->comment('command'),
                    'started_at' => $this->dateTime()->comment('started_at'),
                    'last_run' => $this->dateTime()->comment('last_run'),
                    'next_run' => $this->dateTime()->comment('next_run'),
                    'user_id' => $this->integer()->comment('User'),
                    'status' => $this->integer()->comment('Status'),
                    'created_at' => $this->dateTime()->comment('Created at'),
                    'updated_at' => $this->dateTime()->comment('Updated at'),
                    'deleted_at' => $this->dateTime()->comment('Deleted at'),
                    'created_by' => $this->integer()->comment('Created by'),
                    'updated_by' => $this->integer()->comment('Updated at'),
                    'deleted_by' => $this->integer()->comment('Deleted at'),
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);
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

        $this->dropTable(self::TABLE);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}