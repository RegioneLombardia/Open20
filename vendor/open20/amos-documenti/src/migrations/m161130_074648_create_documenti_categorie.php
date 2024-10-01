<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\documenti\models\DocumentiCategorie;
use yii\db\Migration;

/**
 * Class m161130_074648_create_documenti_categorie
 */
class m161130_074648_create_documenti_categorie extends Migration
{
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = DocumentiCategorie::tableName();
        parent::__construct();
    }

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema($this->tabella, true) === null) {
            $this->createTable($this->tabella, [
                'id' => $this->primaryKey(11),
                'titolo' => $this->string(255)->defaultValue(null)->comment('Titolo'),
                'sottotitolo' => $this->string(255)->defaultValue(null)->comment('Sottotitolo'),
                'descrizione_breve' => $this->string(255)->defaultValue(null)->comment('Descrizione breve'),
                'descrizione' => $this->text()->comment('Descrizione'),
                'filemanager_mediafile_id' => $this->integer(11)->defaultValue(null)->comment('Immagine'),
                'created_at' => $this->dateTime()->defaultValue(null)->comment('Creato il'),
                'updated_at' => $this->dateTime()->defaultValue(null)->comment('Aggiornato il'),
                'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
                'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
                'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
                'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_documenti_categorie_filemanager_mediafile1', $this->tabella, 'filemanager_mediafile_id', 'filemanager_mediafile', 'id');
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
