<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `m210514180000_create_table_user_import_task`.
 */
class m210514_180000_create_table_user_import_task extends Migration
{
    const TABLE = "{{%user_import_task}}";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        /** @var Schema $ts */
        $ts = $this->db->schema->getTableSchema(self::TABLE, true);
        if ($ts !== null)
        {
            echo "La tabella ".self::TABLE." esiste, la elimino! vado po a creare la mia di piattaforma...";
            $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
            if (isset($ts->foreignKeys['fk_event_group_referent_id_id1']) && !empty($ts->foreignKeys['fk_event_group_referent_id_id1'])) {
                $this->dropForeignKey('fk_event_group_referent_id_id1', self::TABLE);
            }
            $this->dropTable(self::TABLE);
            $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        }

        $this->createTable(self::TABLE, [
            'id' => Schema::TYPE_PK,
            'name' => $this->string()->comment('name'),
            'task_date' => $this->dateTime()->comment('Task Date'),
            'user_id' => $this->integer()->comment('User'),
            'tot_input_processed' => $this->integer()->comment('Tot Record Processed'),
            'tot_input_imported' => $this->integer()->comment('Tot Record Imported'),
            'status' => $this->integer()->comment('Tot Record Imported'),
            'accept' =>  $this->integer(),
            'created_at' => $this->dateTime()->comment('Created at'),
            'updated_at' =>  $this->dateTime()->comment('Updated at'),
            'deleted_at' => $this->dateTime()->comment('Deleted at'),
            'created_by' =>  $this->integer()->comment('Created by'),
            'updated_by' =>  $this->integer()->comment('Updated at'),
            'deleted_by' =>  $this->integer()->comment('Deleted at'),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

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
