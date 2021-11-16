<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m160912_144535_create_discussioni_topic extends Migration
{
    const TABLE = '{{%discussioni_topic}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'titolo' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Titolo'",
                'testo' => Schema::TYPE_TEXT . " COMMENT 'Testo'",
                'hints' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'lat' => Schema::TYPE_TEXT . " COMMENT 'Latitudine'",
                'lng' => Schema::TYPE_TEXT . " COMMENT 'Longitudine'",
                'in_evidenza' => "TINYINT(1) DEFAULT '0' COMMENT 'In evidenza'",
                'status' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Stato'",
                'image_id' => Schema::TYPE_INTEGER . "(11) DEFAULT NULL COMMENT 'Immagne'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
                'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'",
            ], $this->db->driverName === 'mysql' ? 'ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_discussioni_topic_filemanager_mediafile1', self::TABLE, 'image_id', 'filemanager_mediafile', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste già";
        }

        return true;
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropTable(self::TABLE);
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }

        return true;
    }
}
