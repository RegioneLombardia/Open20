<?php

use yii\db\Migration;

class m220527_125100_create_table_collaboration_ipr_status_mm extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS     = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_IPR_STATUS    = '{{%collaboration_ipr_status}}';
    const TABLE_COLLABORATION_IPR_STATUS_MM = '{{%collaboration_ipr_status_mm}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_IPR_STATUS_MM, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_IPR_STATUS_MM, [
                'id' => $this->primaryKey(),
                'collaboration_proposals_id' => $this->integer()->notNull()->comment('Collaboration Proposals ID'),
                'collaboration_ipr_status_id' => $this->integer()->notNull()->comment('Collaboration IPR Status ID'),

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

        $this->addForeignKey('fk_collaboration_ipr_status_id_mm_collaboration_proposals', self::TABLE_COLLABORATION_IPR_STATUS_MM, 'collaboration_proposals_id', self::TABLE_COLLABORATION_PROPOSALS, 'id');
        $this->addForeignKey('fk_collaboration_ipr_status_id_mm_collaboration_ipr_status', self::TABLE_COLLABORATION_IPR_STATUS_MM, 'collaboration_ipr_status_id', self::TABLE_COLLABORATION_IPR_STATUS, 'id');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_IPR_STATUS_MM);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
