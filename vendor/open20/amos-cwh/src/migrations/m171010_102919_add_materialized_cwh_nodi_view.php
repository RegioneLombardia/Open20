<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\migrations
 * @category   CategoryName
 */

use open20\amos\cwh\models\CwhNodi;
use yii\db\Migration;
use yii\db\Schema;

class m171010_102919_add_materialized_cwh_nodi_view extends Migration
{
    const TABLE = '{{%cwh_nodi_mt}}';

    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_STRING . "(100)",
                'cwh_config_id' => Schema::TYPE_BIGINT,
                'record_id' => Schema::TYPE_INTEGER,
                'classname' => Schema::TYPE_STRING . "(255)",
                'visibility' =>Schema::TYPE_INTEGER ,
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);

            $this->addPrimaryKey('pk',self::TABLE,['id']);
            $this->createIndex('index_cwh_config_id',self::TABLE,['cwh_config_id']);
            $this->createIndex('index_record_id',self::TABLE,['record_id']);
            $this->createIndex('index_classname',self::TABLE,['classname']);
            $this->createIndex('index_visibility',self::TABLE,['visibility']);

            CwhNodi::mustReset();

        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
            return true;
        }
    }

    public function down()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropIndex('index_cwh_config_id',self::TABLE);
            $this->dropIndex('index_record_id',self::TABLE);
            $this->dropIndex('index_classname',self::TABLE);
            $this->dropIndex('index_visibility',self::TABLE);
            $this->dropPrimaryKey('pk',self::TABLE);
            $this->dropTable(self::TABLE);
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
            return true;
        }
    }
}
