<?php

use yii\db\Migration;

class m220527_093500_create_table_collaboration_type extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_TYPE      = '{{%collaboration_type}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_TYPE, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_TYPE, [
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

        $this->addForeignKey('fk_collaboration_proposals_collaboration_type_id', self::TABLE_COLLABORATION_PROPOSALS, 'collaboration_type_id', self::TABLE_COLLABORATION_TYPE, 'id');

        $this->insert(self::TABLE_COLLABORATION_TYPE, [
            'id' => 1,
            'name' => 'Offerta di tecnologia',
        ]);
        $this->insert(self::TABLE_COLLABORATION_TYPE, [
            'id' => 2,
            'name' => 'Ricerca di tecnologia',
        ]);
        $this->insert(self::TABLE_COLLABORATION_TYPE, [
            'id' => 3,
            'name' => 'Offerta commerciale',
        ]);
        $this->insert(self::TABLE_COLLABORATION_TYPE, [
            'id' => 4,
            'name' => 'Ricerca fornitore',
        ]);
        $this->insert(self::TABLE_COLLABORATION_TYPE, [
            'id' => 5,
            'name' => 'Collaborazione a progetto di ricerca e sviluppo',
        ]);
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_TYPE);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
