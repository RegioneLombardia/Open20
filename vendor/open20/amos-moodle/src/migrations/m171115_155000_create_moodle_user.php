<?php
use open20\amos\core\migration\AmosMigrationTableCreation;
use open20\amos\moodle\models\MoodleUser;


/**
* Class m171115_155000_create_moodle_user*/
class m171115_155000_create_moodle_user extends  AmosMigrationTableCreation
{

    private $tabella = null;

    public function __construct()
    {
        $this->tabella = MoodleUser::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema($this->tabella, true) === null) {
            $this->createTable($this->tabella, [
                'id' => $this->primaryKey(11),
                'moodle_userid' => $this->integer(11)->notNull()->comment('User id in Moodle'),
                'moodle_username' => $this->string(255)->notNull()->comment('Username in Moodle'),
                'moodle_password' => $this->string(255)->notNull()->comment('Password in Moodle'),
                'moodle_email' => $this->string(255)->notNull()->comment('Email in Moodle'),
                'moodle_name' => $this->string(255)->defaultValue(null)->comment('User name in Moodle'),
                'moodle_surname' => $this->string(255)->defaultValue(null)->comment('User surname in Moodle'),
                'moodle_token' => $this->string(255)->defaultValue(null)->comment('User token in Moodle'),
                'user_id' => $this->integer(11)->notNull()->comment('user'),
                'created_at' => $this->dateTime()->defaultValue(null)->comment('Creato il'),
                'updated_at' => $this->dateTime()->defaultValue(null)->comment('Aggiornato il'),
                'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
                'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
                'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
                'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('moodle_user_idfk', $this->tabella, 'user_id', 'user', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        return true;
    }

    public function safeDown()
    {
        if ($this->db->schema->getTableSchema($this->tabella, true) !== null) {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            $this->dropTable($this->tabella);
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        } else {
            echo "Nessuna cancellazione eseguita in quanto la tabella non esiste";
        }

        return true;
    }
}
