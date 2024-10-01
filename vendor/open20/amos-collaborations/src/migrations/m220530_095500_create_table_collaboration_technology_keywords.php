<?php

use yii\db\Migration;

class m220530_095500_create_table_collaboration_technology_keywords extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS           = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_TECHNOLOGY_KEYWORDS = '{{%collaboration_technology_keywords}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_TECHNOLOGY_KEYWORDS, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_TECHNOLOGY_KEYWORDS, [
                'id' => $this->primaryKey(),
                'description' => $this->string()->notNull()->comment('Description'),
                'type' => $this->integer()->notNull()->comment('Type'),

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

    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_TECHNOLOGY_KEYWORDS);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
