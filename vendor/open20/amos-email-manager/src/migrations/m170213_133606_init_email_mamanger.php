<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m170213_133606_init_email_mamanger extends Migration
{
    const TABLE = '{{%email_template}}';
    const TABLE_SPOOL = '{{%email_spool}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'subject' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'heading' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'message' => Schema::TYPE_TEXT . " DEFAULT NULL",
                'created_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL ",
                'updated_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL ",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE_SPOOL, true) === null) {
            $this->createTable(self::TABLE_SPOOL, [
                'id' => Schema::TYPE_PK,
                'transport' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'template' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'priority' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'status' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'model_name' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'model_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'to_address' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'from_address' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'subject' => Schema::TYPE_STRING . "(255) DEFAULT NULL",
                'message' => Schema::TYPE_TEXT . " DEFAULT NULL",
                'bcc' => Schema::TYPE_TEXT . " DEFAULT NULL",
                'files' => "LONGBLOB DEFAULT NULL",
                'sent' => Schema::TYPE_INTEGER . " DEFAULT NULL",
                'created_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_INTEGER . " NULL DEFAULT NULL COMMENT 'Aggiornato il'",
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->createIndex("transport", self::TABLE_SPOOL, ["transport"]);
            $this->createIndex("template", self::TABLE_SPOOL, ["template"]);
            $this->createIndex("status", self::TABLE_SPOOL, ["status"]);
            $this->createIndex("to_address", self::TABLE_SPOOL, ["to_address"]);
            $this->createIndex("from_address", self::TABLE_SPOOL, ["from_address"]);
            $this->createIndex("subject", self::TABLE_SPOOL, ["subject"]);
            $this->createIndex("sent", self::TABLE_SPOOL, ["sent"]);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
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

        if ($this->db->schema->getTableSchema(self::TABLE_SPOOL, true) !== null) {
            $this->dropTable(self::TABLE_SPOOL);
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }

        return true;
    }
}
