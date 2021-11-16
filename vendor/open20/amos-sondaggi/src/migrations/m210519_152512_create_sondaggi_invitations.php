<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210519_152512_create_sondaggi_invitations
 */
class m210519_152512_create_sondaggi_invitations extends Migration
{
    const TABLE          = '{{%sondaggi_invitations}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                'id' => Schema::TYPE_PK,
                'sondaggi_id' => Schema::TYPE_INTEGER." NOT NULL",
                'name' => Schema::TYPE_STRING,
                'query' => Schema::TYPE_TEXT,
                'created_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Creato il'",
                'updated_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Aggiornato il'",
                'deleted_at' => Schema::TYPE_DATETIME." NULL DEFAULT NULL COMMENT 'Cancellato il'",
                'created_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Creato da'",
                'updated_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Aggiornato da'",
                'deleted_by' => Schema::TYPE_INTEGER." NULL DEFAULT NULL COMMENT 'Cancellato da'",
                ],
                $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1'
                        : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella ".self::TABLE." esiste gia'";
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropTable(self::TABLE);
        }

        echo "Cancellazione eseguita correttamente";

        return true;
    }
}