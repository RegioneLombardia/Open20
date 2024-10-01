<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210513_224647_create_sondaggi_invitation_mm
 */
class m210513_224647_create_sondaggi_invitation_mm extends Migration
{
    const TABLE          = '{{%sondaggi_invitation_mm}}';
    const TABLE_CONFIG   = '{{%sondaggi_config_contents}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_CONFIG, true) === null) {
            $this->createTable(self::TABLE_CONFIG,
                [
                'id' => Schema::TYPE_PK,
                'class' => Schema::TYPE_TEXT,
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
            echo "Nessuna creazione eseguita in quanto la tabella ".self::TABLE_CONFIG." esiste gia'";
        }
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE,
                [
                'id' => Schema::TYPE_PK,
                'sondaggi_id' => Schema::TYPE_INTEGER." NOT NULL",
                'invitation_class' => Schema::TYPE_INTEGER." NOT NULL",
                'invitation_id' => Schema::TYPE_INTEGER." NOT NULL",
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

        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            $this->dropTable(self::TABLE_CONFIG);
        }

        echo "Cancellazione eseguita correttamente";

        return true;
    }
}