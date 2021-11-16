<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200306_073522_create_sondaggi_validation
 */
class m200306_073522_create_sondaggi_validation extends Migration
{
    const TABLE         = '{{%sondaggi_domande_rule_mm}}';
    const TABLE_TIPO    = '{{%sondaggi_domande_rule}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_TIPO, true) === null) {
            $this->createTable(self::TABLE_TIPO,
                [
                'id' => Schema::TYPE_PK,
                'nome' => Schema::TYPE_STRING."(255) NOT NULL",
                'descrizione' => Schema::TYPE_TEXT." NULL DEFAULT NULL",
                'custom' => Schema::TYPE_INTEGER." NULL DEFAULT '0'",
                'codice_custom' => Schema::TYPE_TEXT." NULL DEFAULT NULL",
                'created_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Cancellato da'",
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);
            
        }
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                'id' => Schema::TYPE_PK,
                'sondaggi_domande_id' => Schema::TYPE_INTEGER." NOT NULL",
                'sondaggi_domande_rule_id' => Schema::TYPE_INTEGER." NOT NULL",
                'created_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Cancellato da'",
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);

            $this->addForeignKey(
                'fk-sondaggi_domande_rule_id-k1', self::TABLE, 'sondaggi_domande_id', self::TABLE_DOMANDE, 'id'
            );
            $this->addForeignKey(
                'fk-sondaggi_domande_rule_id-k2', self::TABLE, 'sondaggi_domande_rule_id', self::TABLE_TIPO, 'id'
            );
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropForeignKey(self::TABLE, 'fk-sondaggi_domande_rule_id-k1');
            $this->dropForeignKey(self::TABLE, 'fk-sondaggi_domande_rule_id-k2');
            $this->dropTable(self::TABLE);
        }
        if ($this->db->schema->getTableSchema(self::TABLE_TIPO, true) !== null) {
            $this->dropTable(self::TABLE_TIPO);
        }

        echo "Cancellazione eseguita correttamente";

        return true;
    }
}