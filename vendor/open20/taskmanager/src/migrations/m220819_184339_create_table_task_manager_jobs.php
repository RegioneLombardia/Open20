<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `m220819_184339_create_table_task_manager_jobs`.
 */
class m220819_184339_create_table_task_manager_jobs extends Migration
{
    const TABLE = "task_manager_jobs";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                    'id' => Schema::TYPE_PK,
                    'command' => $this->string()->comment('Task to launch'),
                    'module' => $this->string()->comment('Module that launched the command'),
                    'channel' => $this->integer()->defaultValue(1)->comment('Default task channel to avoid overrides'),
                    'status' => $this->integer()->defaultValue(0)->comment('Task status'),
                    'data' => $this->text()->comment('Additional data'),
                    'filename' => $this->string()->comment('File name to use for export'),
                    'created_at' => $this->dateTime()->null()->comment('Created at'),
                    'updated_at' => $this->dateTime()->null()->comment('Updated at'),
                    'deleted_at' => $this->dateTime()->null()->comment('Deleted at'),
                    'created_by' => $this->integer()->null()->comment('Created by'),
                    'updated_by' => $this->integer()->null()->comment('Updated at'),
                    'deleted_by' => $this->integer()->null()->comment('Deleted at'),
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
