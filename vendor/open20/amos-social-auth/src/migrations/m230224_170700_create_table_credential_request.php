<?php

use yii\db\Migration;

class m230224_170700_create_table_credential_request extends Migration
{
    const TABLE = '{{%credential_request}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'nome' => $this->string()->notNull()->defaultValue(null)->comment('Nome'),
                'cognome' => $this->string()->null()->defaultValue(null)->comment('Cognome'),
                'motivazione' => $this->string()->null()->defaultValue(null)->comment('Motivazioni della richiesta di credenziali'),
                'created_by' => $this->integer()->notNull()->defaultValue(null),
                'created_at' => $this->dateTime()->notNull()->defaultValue(null),
                'updated_by' => $this->integer()->notNull()->defaultValue(null),
                'updated_at' => $this->dateTime()->notNull()->defaultValue(null),
                'deleted_by' => $this->integer()->notNull()->defaultValue(null),
                'deleted_at' => $this->dateTime()->notNull()->defaultValue(null),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        return true;
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);

        return true;
    }
}
