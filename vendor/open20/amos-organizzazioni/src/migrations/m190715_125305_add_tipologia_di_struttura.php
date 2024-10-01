<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m190715_125305_add_tipologia_di_struttura extends Migration
{

    const TABLE_PROFILO_TIPOLOGIA_STRUTTURA = '{{%profilo_tipologia_struttura}}';

    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");
        $charset = ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);
        $auto_increment = ($this->db->driverName === 'mysql' ? ' AUTO_INCREMENT=1' : null);

        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO_TIPOLOGIA_STRUTTURA, true) === null) {
            $this->createTable(self::TABLE_PROFILO_TIPOLOGIA_STRUTTURA, [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Tipo di struttura'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            ],$charset.$auto_increment);

            $this->execute("
                INSERT INTO ".self::TABLE_PROFILO_TIPOLOGIA_STRUTTURA." (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
                ('Enti pubblici e Pubblica Amministrazione (Comuni, Province, ASL, Associazioni di enti pubblici, Istituti di formazione, Scuole, etc.)',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Sistema della ricerca (Università, Enti pubblici e privati di ricerca, Laboratori di ricerca, etc.)',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Associazioni e soggetti del sistema socio-economico regionale rappresentativi di bisogni collettivi e sociali',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Impresa',	NOW(),	NOW(),	NULL,	1,	1,	NULL);
            ");
        }
        
        $this->createIndex(
            'content_idx',
            self::TABLE_PROFILO_TIPOLOGIA_STRUTTURA,
            ['name'],
            false
        );
        
        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
    }

    public function safeDown()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");

        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO_TIPOLOGIA_STRUTTURA, true) !== null) {
            $this->dropTable(self::TABLE_PROFILO_TIPOLOGIA_STRUTTURA);
        }

        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * @param $table_name
     * @return mixed
     */
    private function cleanTableName($table_name)
    {
        return str_replace(["{{%", "}}"], ["", ""], $table_name);
    }
}

