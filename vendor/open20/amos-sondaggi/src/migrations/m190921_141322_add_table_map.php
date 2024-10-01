<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190921_141322_add_table_map
 */
class m190921_141322_add_table_map extends Migration
{
    const TABLE_MAP = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_MAP, true) === null) {
            $this->createTable(self::TABLE_MAP,
                [
                'id' => Schema::TYPE_PK,
                'campo' => Schema::TYPE_STRING."(255) DEFAULT NULL COMMENT 'campo'",
                'tabella' => Schema::TYPE_STRING."(255) DEFAULT NULL COMMENT 'tabella'",
                'descrizione' => " TEXT DEFAULT NULL COMMENT 'descrizione'",
                'obbligatorio' => Schema::TYPE_INTEGER." DEFAULT 0 COMMENT 'obbligatorio'",
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
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_MAP);
        echo "Cancellazione della tabella avvenuta correttamente.";
        return true;
    }
}