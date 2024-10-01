<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use yii\db\Schema;

class m150803_000005_create_cwh_nodi extends \yii\db\Migration
{
    const TABLE = '{{%cwh_nodi}}';

    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
        $this->createTable(self::TABLE, [
            'id' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Id (CLASSNAME-RECORD_ID)'",
            'cwh_config_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'record_id' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL",
            'classname' => Schema::TYPE_STRING . "(255) NULL DEFAULT NULL",
            'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
            'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
            'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
            'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
            'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'",
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);
        $this->addPrimaryKey('id', self::TABLE, 'id');
        $this->createIndex('fk_cwh_nodi_cwh_config1_idx', self::TABLE, 'cwh_config_id');
        $this->addForeignKey('fk_cwh_nodi_cwh_config1', self::TABLE, 'cwh_config_id', 'cwh_config', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
            return true;
        }
    }

    public function down()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropForeignKey('fk_cwh_nodi_cwh_config1', self::TABLE);
            $this->dropIndex('fk_cwh_nodi_cwh_config1_idx', self::TABLE);
            $this->dropPrimaryKey('id', self::TABLE);
            $this->dropTable(self::TABLE);
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
            return true;
        }
    }

}