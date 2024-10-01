<?php

use yii\db\Migration;

class m220527_173000_create_table_collaboration_partner_type_and_size extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS             = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE = '{{%collaboration_partner_type_and_size}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
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

        $this->insert(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
            'id' => 1,
            'name' => 'Università',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
            'id' => 2,
            'name' => 'Ente di ricerca',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
            'id' => 3,
            'name' => 'Grande azienda',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
            'id' => 4,
            'name' => 'Microimpresa',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
            'id' => 5,
            'name' => 'PMI < 50',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
            'id' => 6,
            'name' => 'PMI > 50',
        ]);
        $this->insert(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, [
            'id' => 7,
            'name' => 'Altro',
        ]);
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
