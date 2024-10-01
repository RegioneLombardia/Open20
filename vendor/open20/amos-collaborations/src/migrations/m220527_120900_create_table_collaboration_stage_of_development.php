<?php

use yii\db\Migration;

class m220527_120900_create_table_collaboration_stage_of_development extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS            = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT = '{{%collaboration_stage_of_development}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, [
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

        $this->addForeignKey('fk_collaboration_proposals_collaboration_stage_of_development_id', self::TABLE_COLLABORATION_PROPOSALS, 'stage_of_development_id', self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, 'id');

        $this->insert(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, [
            'id' => 1,
            'name' => 'Sul mercato',
        ]);
        $this->insert(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, [
            'id' => 2,
            'name' => 'Dimostratore',
        ]);
        $this->insert(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, [
            'id' => 3,
            'name' => 'Prototipo funzionale',
        ]);
        $this->insert(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, [
            'id' => 4,
            'name' => 'Prototipo concettuale',
        ]);
        $this->insert(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT, [
            'id' => 5,
            'name' => 'Ricerca e sviluppo',
        ]);
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_STAGE_OF_DEVELOPMENT);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
