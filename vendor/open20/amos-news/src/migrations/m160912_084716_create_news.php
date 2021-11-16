<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m160912_084716_create_news extends Migration
{
    const TABLE = '{{%news}}';
    
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'titolo' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Titolo'",
                'sottotitolo' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Sottotitolo'",
                'descrizione_breve' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Descrizione breve'",
                'descrizione' => Schema::TYPE_TEXT . " COMMENT 'Descrizione'",
                'metakey' => Schema::TYPE_TEXT . " COMMENT 'Meta key'",
                'metadesc' => Schema::TYPE_TEXT . " COMMENT 'Meta descrizione'",
                'primo_piano' => "TINYINT(1) DEFAULT '0' COMMENT 'Primo piano'",
                'immagine' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'hits' => Schema::TYPE_INTEGER . " DEFAULT NULL COMMENT 'Visualizzazioni'",
                'abilita_pubblicazione' => "TINYINT(1) DEFAULT '0' COMMENT 'Abilita pubblicazione'",
                'in_evidenza' => "TINYINT(1) DEFAULT '0' COMMENT 'In evidenza'",
                'data_pubblicazione' => Schema::TYPE_DATE . " DEFAULT NULL COMMENT 'Data pubblicazione'",
                'data_rimozione' => Schema::TYPE_DATE . " DEFAULT NULL COMMENT 'Data fine pubblicazione'",
                'news_categorie_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Categoria'",
                'status' => Schema::TYPE_STRING . "(255) DEFAULT NULL COMMENT 'Stato'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
                'version' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Versione numero'"
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_news_news_categorie1', self::TABLE, 'news_categorie_id', 'news_categorie', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
        
        return true;
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null)
        {
            $this->dropForeignKey('fk_news_news_categorie1', self::TABLE);
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            $this->dropTable(self::TABLE);
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        }
        else
        {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }
        
        return true;
    }
}
