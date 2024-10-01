<?php

use yii\db\Migration;

class m231117_100900_create_table_punteggi_tot_users_gamif extends Migration
{
    const TABLE = '{{%punteggi_tot_users_gamif}}';


    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            $this->createTable(self::TABLE, [
                'id' => $this->primaryKey(),
                'gamification_id' => $this->integer()->notNull()->defaultValue(null),
                'posizione' => $this->integer()->notNull()->defaultValue(null)->comment('Posizione'),
                'user_id' => $this->integer()->notNull()->defaultValue(null),
                'nome_cognome' => $this->string()->defaultValue(null)->comment('Nome e cognome utente'),
                'totale' => $this->integer()->notNull()->defaultValue(null)->comment("Punteggio totale"),
                'profilo' => $this->integer()->defaultValue(null)->comment('Punteggio profilo'),
                'community' => $this->integer()->defaultValue(null)->comment('Punteggio community'),
                'notizie' => $this->integer()->defaultValue(null)->comment('Punteggio notizie'),
                'documenti' => $this->integer()->defaultValue(null)->comment('Punteggio documenti'),
                'discussioni' => $this->integer()->defaultValue(null)->comment('Punteggio discussioni'),
                'sondaggi' => $this->integer()->defaultValue(null)->comment("Punteggio sondaggi"),

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
