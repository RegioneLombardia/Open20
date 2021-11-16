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

class m160912_144559_create_discussioni_allegati extends Migration
{
    const TABLE = '{{%discussioni_allegati}}';
    
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'titolo' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Titolo'",
                'descrizione' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Titolo'",
                'filemanager_mediafile_id' => Schema::TYPE_PK,
                'discussioni_topic_id' => Schema::TYPE_INTEGER . " DEFAULT NULL COMMENT 'Discussioni Topic ID'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
                'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'",
            ], $this->db->driverName === 'mysql' ? 'ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_discussioni_allegati_filemanager_mediafile1', self::TABLE, 'filemanager_mediafile_id', 'filemanager_mediafile', 'id');
            $this->addForeignKey('fk_discussioni_allegati_discussioni_topic1', self::TABLE, 'discussioni_topic_id', 'discussioni_topic', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste già";
        }
        
        return true;
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null)
        {
            $this->execute("SET foreign_key_checks = 0;");
            $this->dropTable(self::TABLE);
            $this->execute("SET foreign_key_checks = 1;");
        }
        else
        {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }
        
        return true;
    }
}
