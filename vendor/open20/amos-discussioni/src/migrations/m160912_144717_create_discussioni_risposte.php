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

class m160912_144717_create_discussioni_risposte extends Migration
{
    const TABLE = '{{%discussioni_risposte}}';
    
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'testo' => Schema::TYPE_TEXT . " COMMENT 'Testo'",
                'discussioni_topic_id' => Schema::TYPE_INTEGER . " NOT NULL",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
                'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_discussioni_risposte_discussioni_topic1', self::TABLE, 'discussioni_topic_id', 'discussioni_topic', 'id');
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
            $this->dropForeignKey('fk_discussioni_risposte_discussioni_topic1', self::TABLE);
            $this->dropTable(self::TABLE);
        }
        else
        {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }
        
        return true;
    }
}
