<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m210514_180005_create_table_user_import_task_user extends Migration
{
    const TABLE = "{{%user_import_task_user}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null)
        {
            echo "La tabella ".self::TABLE." esiste, la elimino! vado po a creare la mia di piattaforma...";

            if (isset($ts->foreignKeys['fk_user_import_task_user_taks_id1']) && !empty($ts->foreignKeys['fk_event_group_referent_id_id1'])) {
                $this->dropForeignKey('fk_user_import_task_user_taks_id1', self::TABLE);
            }

            if (isset($ts->foreignKeys['fk_user_import_task_user_user_id1']) && !empty($ts->foreignKeys['fk_event_group_referent_id_id1'])) {
                $this->dropForeignKey('fk_user_import_task_user_user_id1', self::TABLE);
            }

            $this->dropTable(self::TABLE);
        }

        $this->createTable(self::TABLE, [
            'id' => Schema::TYPE_PK,
            'user_import_task_id' => $this->integer()->comment('Task'),
            'name' => $this->string()->comment('Name'),
            'surname' => $this->string()->comment('Surname'),
            'email' => $this->string()->comment('Email'),
            'company' => $this->string()->comment('Company'),
            'token' => $this->string()->comment('Token'),
            'expire_date' => $this->dateTime()->comment('Expire Date'),
            'to_send' => $this->integer()->defaultValue(1)->comment('To send'),
            'user_id' => $this->integer()->comment('User'),
            'created_at' => $this->dateTime()->comment('Created at'),
            'updated_at' =>  $this->dateTime()->comment('Updated at'),
            'deleted_at' => $this->dateTime()->comment('Deleted at'),
            'created_by' =>  $this->integer()->comment('Created by'),
            'updated_by' =>  $this->integer()->comment('Updated at'),
            'deleted_by' =>  $this->integer()->comment('Deleted at'),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

        $this->addForeignKey('fk_user_import_task_user_taks_id1',self::TABLE, 'user_import_task_id', 'user_import_task', 'id');
        $this->addForeignKey('fk_user_import_task_user_user_id1', self::TABLE, 'user_id', 'user', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropForeignKey('fk_user_import_task_user_taks_id1', self::TABLE);
        $this->dropForeignKey('fk_user_import_task_user_user_id1', self::TABLE);
        $this->dropTable(self::TABLE);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
