<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

use yii\db\Schema;

/**
 * Default migration for the `organizzazioni` plugin
 */
class m171124_172315_init_module_organizzazioni extends \yii\db\Migration
{

    const TABLE_ASSIGNMENT_AMOS = '{{%auth_assignment}}';
    const TABLE_RULE_AMOS = '{{%auth_rule}}';
    const TABLE_PERMISSION_AMOS = '{{%auth_item}}';
    const TABLE_PERMISSION_CHILD_AMOS = '{{%auth_item_child}}';
    const TABLE_USER_AMOS = '{{%user}}';
    const TABLE_WIDGET_AMOS = '{{%widgets}}';
    const TABLE_PROFILO = '{{%profilo}}';
    const TABLE_ORGANIZATIONS_PLACES = '{{%organizzazioni_places}}';

    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");
        $charset = ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null);


        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO, true) === null) {

            $this->createTable(self::TABLE_PROFILO, [
                'id' => Schema::TYPE_PK,
                'denominazione' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Denominazione'",
                'partita_iva' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Partita Iva'",
                'codice_fiscale' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Codice Fiscale'",
                'presentazione_della_organizzaz' => Schema::TYPE_TEXT . "  NULL COMMENT 'Presentazione della organizzazione in inglese'",
                'principali_ambiti_di_attivita_organizzazione' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Principali ambiti di attività in cui opera la'",
                'ambiti_tecnologici_su_cui_siet' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Ambiti tecnologici su cui siete interessati a'",
                'tipologia_di_organizzazione' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Tipologia di organizzazione'",
                'forma_legale' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Forma legale'",
                'sito_web' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Sito web'",
                'facebook' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Facebook'",
                'twitter' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Twitter'",
                'linkedin' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Linkedin'",
                'google' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Google+'",
                'logo' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Logo'",
                'allegati' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Allegati'",
                'indirizzo' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Indirizzo'",
                'telefono' => Schema::TYPE_DECIMAL . "  NOT NULL COMMENT 'Telefono'",
                'fax' => Schema::TYPE_DECIMAL . "  NULL COMMENT 'Fax'",
                'email' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Email'",
                'pec' => Schema::TYPE_STRING . "(255) NULL COMMENT 'PEC'",
                'la_sede_legale_e_la_stessa_del' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'La sede legale è la stessa della sede operati'",
                'sede_legale_indirizzo' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Sede legale indirizzo'",
                'sede_legale_telefono' => Schema::TYPE_DECIMAL . "  NULL COMMENT 'Sede legale telefono'",
                'sede_legale_fax' => Schema::TYPE_DECIMAL . "  NULL COMMENT 'Sede legale fax'",
                'sede_legale_email' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Sede legale email'",
                'sede_legale_pec' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Sede legale PEC'",
                'responsabile' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Responsabile'",
                'rappresentante_legale' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Rappresentante legale'",
                'referente_operativo' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Referente operativo'",
                'created_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME . " NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Cancellato da'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        }
        if ($this->db->schema->getTableSchema(self::TABLE_ORGANIZATIONS_PLACES, true) === null) {
            $this->createTable(self::TABLE_ORGANIZATIONS_PLACES, [
                'place_id' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Codice recupero place'",
                'place_response' => Schema::TYPE_TEXT . " NULL COMMENT 'Risposta'",
                'place_type' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Tipologia di recupero dati'",
                'country' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Paese'",
                'region' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Regione'",
                'province' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Provincia'",
                'postal_code' => Schema::TYPE_STRING . "(255) NULL COMMENT 'CAP'",
                'city' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Città'",
                'address' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Via/Piazza'",
                'street_number' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Numero civico'",
                'latitude' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Latitudine'",
                'longitude' => Schema::TYPE_STRING . "(255) NULL COMMENT 'Longitudine'",
            ], $charset);

            $this->addPrimaryKey("pk_".$this->cleanTableName(self::TABLE_ORGANIZATIONS_PLACES)."_place_id", self::TABLE_ORGANIZATIONS_PLACES, "place_id");
        }

        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
    }

    public function safeDown()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");

        if ($this->db->schema->getTableSchema(self::TABLE_PROFILO, true) !== null) {
            $this->dropTable(self::TABLE_PROFILO);
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