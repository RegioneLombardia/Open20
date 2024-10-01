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

class m150803_000008_create_cwh_pubblicazioni_cwh_nodi_validatori_mm extends \yii\db\Migration
{
    const TABLE = '{{%cwh_pubblicazioni_cwh_nodi_validatori_mm}}';

    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'cwh_pubblicazioni_id' => Schema::TYPE_STRING . "(255) NOT NULL",
                'cwh_nodi_id' => Schema::TYPE_STRING . "(255) NOT NULL",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
                'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);
            $this->addForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_nodi3', self::TABLE, 'cwh_nodi_id', 'cwh_nodi', 'id');
            $this->addForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_pubblicazioni3', self::TABLE, 'cwh_pubblicazioni_id', 'cwh_pubblicazioni', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
            return true;
        }
    }

    public function down()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_nodi3', self::TABLE);
            $this->dropForeignKey('fk_cwh_pubblicazioni_cwh_nodi_mm_cwh_pubblicazioni3', self::TABLE);
            $this->dropTable(self::TABLE);
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
            return true;
        }
    }

}