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

class m171212_164705_add_legalform_type_pmi extends Migration
{

    const TABLE_PROFILO_TYPES_PMI = '{{%profilo_types_pmi}}';
    const TABLE_PROFILO_LEGAL_FORM = '{{%profilo_legal_form}}';

    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");
        $charset = ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);
        $auto_increment = ($this->db->driverName === 'mysql' ? ' AUTO_INCREMENT=1' : null);


        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO_TYPES_PMI, true) === null) {
            $this->createTable(self::TABLE_PROFILO_TYPES_PMI, [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Tipo PMI'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            ],$charset.$auto_increment);

            $this->execute("
                INSERT INTO ".self::TABLE_PROFILO_TYPES_PMI." (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
                ('Grande azienda',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Media Impresa',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Piccola impresa',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Startup',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Startup innovativa',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Pubblica amministrazione',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Fondazione',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Istituzioni pubbliche',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Distretti tecnologici',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Spin off',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Centro di ricerca',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Università',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Altro',	NOW(),	NOW(),	NULL,	1,	1,	NULL);
            ");
        }
        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO_LEGAL_FORM, true) === null) {
            $this->createTable(self::TABLE_PROFILO_LEGAL_FORM, [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Tipo PMI'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            ],$charset.$auto_increment);

            $this->execute("
                INSERT INTO ".self::TABLE_PROFILO_LEGAL_FORM." (`name`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
                ('Imprenditore individuale, libero professionista e lavoratore autonomo',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Società di persone',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Società di capitali',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Società Cooperativa',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                ('Consorzio di diritto privato ed altre forme di cooperazione fra imprese',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Ente privato con personalità giuridica',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Ente privato senza personalità giuridica',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Impresa o ente privato costituito all’estero non altrimenti classificabile che svolge  una attività economica in Italia',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Organo costituzionale o a rilevanza costituzionale',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Amministrazione dello Stato',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Autorità indipendente',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Regione e autonomia locale',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Azienda o ente del servizio sanitario nazionale',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Istituto, scuola e università pubblica',	NOW(),	NOW(),	NULL,	1,	1,	NULL),
                 ('Ente pubblico non economico',	NOW(),	NOW(),	NULL,	1,	1,	NULL);
            ");
        }
        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
    }

    public function safeDown()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");

        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO_TYPES_PMI, true) !== null) {
            $this->dropTable(self::TABLE_PROFILO_TYPES_PMI);
        }
        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO_LEGAL_FORM, true) !== null) {
            $this->dropTable(self::TABLE_PROFILO_LEGAL_FORM);
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

