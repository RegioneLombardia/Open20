<?php

use yii\db\Migration;

class m211217_150236_create_table_platform_configs extends Migration
{
    const TABLE = '{{%platform_configs}}';



    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'module' => $this->string()->null()->defaultValue(null)->comment('Module'),
                'key' => $this->string()->null()->defaultValue(null)->comment('Key'),
                'value' => $this->text()->null()->defaultValue(null)->comment('Value'),
                'created_by' => $this->integer()->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_by' => $this->integer()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
                'deleted_by' => $this->integer()->null()->defaultValue(null),
                'deleted_at' => $this->dateTime()->null()->defaultValue(null),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }




        return true;
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
