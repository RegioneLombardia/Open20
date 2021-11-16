<?php
use open20\amos\core\migration\AmosMigrationTableCreation;
use open20\amos\moodle\models\MoodleCategory;


/**
* Class m180116_142500_create_moodle_category*/
class m180116_142500_create_moodle_category extends AmosMigrationTableCreation
{

    private $tabella = null;

    public function __construct()
    {
        $this->tabella = MoodleCategory::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema($this->tabella, true) === null) {
            $this->createTable($this->tabella, [
                'id' => $this->primaryKey(11),
                'moodle_categoryid' => $this->integer(11)->notNull()->comment('Category id in Moodle'),
                'community_id' => $this->integer(11)->comment('Community id'),
                'created_at' => $this->dateTime()->defaultValue(null)->comment('Creato il'),
                'updated_at' => $this->dateTime()->defaultValue(null)->comment('Aggiornato il'),
                'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
                'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
                'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
                'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            //$this->addForeignKey('community_idfk', $this->tabella, 'community_id', 'community', 'id');
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
