<?php

use yii\db\Migration;

class m220527_123000_create_table_collaboration_ipr_status extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS  = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_IPR_STATUS = '{{%collaboration_ipr_status}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_IPR_STATUS, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_IPR_STATUS, [
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

        $this->insert(self::TABLE_COLLABORATION_IPR_STATUS, [
            'id' => 1,
            'name' => 'Nessuno',
        ]);
        $this->insert(self::TABLE_COLLABORATION_IPR_STATUS, [
            'id' => 2,
            'name' => 'Segreto industriale',
        ]);
        $this->insert(self::TABLE_COLLABORATION_IPR_STATUS, [
            'id' => 3,
            'name' => 'Brevetto depositato',
        ]);
        $this->insert(self::TABLE_COLLABORATION_IPR_STATUS, [
            'id' => 4,
            'name' => 'Brevetto concesso',
        ]);
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_IPR_STATUS);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
