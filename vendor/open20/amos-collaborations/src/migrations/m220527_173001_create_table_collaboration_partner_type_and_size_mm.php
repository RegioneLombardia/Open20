<?php

use yii\db\Migration;

class m220527_173001_create_table_collaboration_partner_type_and_size_mm extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS                = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE    = '{{%collaboration_partner_type_and_size}}';
    const TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE_MM = '{{%collaboration_partner_type_and_size_mm}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE_MM, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE_MM, [
                'id' => $this->primaryKey(),
                'collaboration_proposals_id' => $this->integer()->notNull()->comment('Collaboration Proposals ID'),
                'collaboration_partner_type_and_size_id' => $this->integer()->notNull()->comment('Collaboration Partner Type and Size ID'),

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

        $this->addForeignKey('fk_collaboration_partner_type_and_size_id_proposals', self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE_MM, 'collaboration_proposals_id', self::TABLE_COLLABORATION_PROPOSALS, 'id');
        $this->addForeignKey('fk_collaboration_partner_type_and_size_id_typesize', self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE_MM, 'collaboration_partner_type_and_size_id', self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE, 'id');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_PARTNER_TYPE_AND_SIZE_MM);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
