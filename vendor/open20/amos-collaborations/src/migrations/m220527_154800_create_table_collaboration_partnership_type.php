<?php

use yii\db\Migration;

class m220527_154800_create_table_collaboration_partnership_type extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS        = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_PARTNERSHIP_TYPE = '{{%collaboration_partnership_type}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_PARTNERSHIP_TYPE, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_PARTNERSHIP_TYPE, [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->comment('Name'),
                'description' => $this->string()->null()->defaultValue(null)->comment('Description'),

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

        $this->insert(self::TABLE_COLLABORATION_PARTNERSHIP_TYPE, [
            'id' => 1,
            'name' => 'Investimento',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNERSHIP_TYPE, [
            'id' => 2,
            'name' => 'Accordo commerciale, licenza, collaborazione tecnica',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNERSHIP_TYPE, [
            'id' => 3,
            'name' => 'Accordo di ricerca e sviluppo',
        ]);
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_PARTNERSHIP_TYPE);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
