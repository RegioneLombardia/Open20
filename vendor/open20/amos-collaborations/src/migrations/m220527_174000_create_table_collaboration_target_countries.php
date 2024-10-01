<?php

use yii\db\Migration;

class m220527_174000_create_table_collaboration_target_countries extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS        = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_TARGET_COUNTRIES = '{{%collaboration_target_countries}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_TARGET_COUNTRIES, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_TARGET_COUNTRIES, [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->comment('Name'),
                'alpha_2' => $this->string(2)->notNull()->comment('Alpha 2 Code'),
                'alpha_3' => $this->string(3)->notNull()->comment('Alpha 3 Code'),
                'country_code' => $this->integer()->notNull()->comment('Country Code'),
                'iso_3166_2' => $this->string()->null()->defaultValue(null)->comment('ISO_3166-2'),
                'region' => $this->string()->null()->defaultValue(null)->comment('Region'),
                'sub_region' => $this->string()->null()->defaultValue(null)->comment('Sub-Region'),
                'intermediate_region' => $this->string()->null()->defaultValue(null)->comment('Intermediate Region'),
                'region_code' => $this->integer()->null()->defaultValue(null)->comment('Region Code'),
                'sub_region_code' => $this->integer()->null()->defaultValue(null)->comment('Sub-Region Code'),
                'intermediate_region_code' => $this->integer()->null()->defaultValue(null)->comment('Intermediate Region Code'),

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
        $this->dropTable(self::TABLE_COLLABORATION_TARGET_COUNTRIES);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
