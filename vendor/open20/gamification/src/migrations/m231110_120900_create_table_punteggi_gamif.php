<?php

use yii\db\Migration;

class m231110_120900_create_table_punteggi_gamif extends Migration
{
    const TABLE = '{{%punteggi_gamif}}';


    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'gamification_id' => $this->integer()->notNull()->defaultValue(null),
                'content_id' => $this->integer()->notNull()->defaultValue(null),
                'datetime_attivita' => $this->dateTime()->notNull()->defaultValue(null)->comment("Data attività"),
                'user_profile_id' => $this->integer()->notNull()->defaultValue(null)->comment("ID Profilo Utente"),
                'username' => $this->string(255)->notNull()->defaultValue(null)->comment('Username'),
                'nome' => $this->string(255)->notNull()->defaultValue(null)->comment('Nome utente'),
                'cognome' => $this->string(255)->notNull()->defaultValue(null)->comment('Cognome utente'),
                'email' => $this->string(255)->notNull()->defaultValue(null)->comment('Email utente'),
                'attivita' => $this->integer()->notNull()->defaultValue(null)->comment('Tipo attività'),
                'punteggio' => $this->integer()->notNull()->defaultValue(null)->comment("Punteggio assegnato per l'attività"),

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
