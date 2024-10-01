<?php

use yii\db\Migration;

class m231103_100900_create_table_gamification extends Migration
{
    const TABLE_VIDEO = '{{%gamification}}';


    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_VIDEO, true) === null) {
            $this->createTable(self::TABLE_VIDEO, [
                'id' => $this->primaryKey(),
                'titolo' => $this->string(255)->notNull()->defaultValue(null)->comment('Titolo della Gamification'),
                'descrizione' => $this->string(255)->notNull()->defaultValue(null)->comment('Descrizione della Gamification'),
                'starting_date' => $this->dateTime()->notNull()->defaultValue(null)->comment('Data inizio della Gamification'),
                'finish_date' => $this->dateTime()->notNull()->defaultValue(null)->comment('Data fine della Gamification'),
                'status' => $this->string()->notNull()->defaultValue(null)->comment('Stato'),

                'utente_iscrizione' => $this->integer()->defaultValue(200),
                'utente_foto' => $this->integer()->defaultValue(150),
                'utente_colleg' => $this->integer()->defaultValue(10),
                'community_creazione' => $this->integer()->defaultValue(100),
                'community_iscrizione' => $this->integer()->defaultValue(20),
                'notizie_creazione' => $this->integer()->defaultValue(50),
                'notizie_commento' => $this->integer()->defaultValue(20),
                'notizie_like' => $this->integer()->defaultValue(20),
                'documenti_creazione' => $this->integer()->defaultValue(50),
                'discussioni_creazione' => $this->integer()->defaultValue(100),
                'discussioni_commento' => $this->integer()->defaultValue(20),
                'discussioni_like' => $this->integer()->defaultValue(20),
                'sondaggi_partec' => $this->integer()->defaultValue(50),

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
        $this->dropTable(self::TABLE_VIDEO);

        return true;
    }
}
