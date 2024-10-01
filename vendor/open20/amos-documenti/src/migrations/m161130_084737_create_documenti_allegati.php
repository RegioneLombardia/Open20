<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m161130_084737_create_documenti_allegati
 */
class m161130_084737_create_documenti_allegati extends Migration
{
    private $tabella = null;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->tabella = 'documenti_allegati';
        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema($this->tabella, true) === null) {
            $this->createTable($this->tabella, [
                'filemanager_mediafile_id' => $this->primaryKey(11),
                'titolo' => $this->string(255)->notNull()->comment('Titolo'),
                'descrizione' => $this->text()->comment('Descrizione'),
                'documenti_id' => $this->integer(11)->notNull(),
                'created_at' => $this->dateTime()->defaultValue(null)->comment('Creato il'),
                'updated_at' => $this->dateTime()->defaultValue(null)->comment('Aggiornato il'),
                'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
                'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
                'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
                'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_documenti_allegati_documenti1_idx', $this->tabella, 'documenti_id', 'documenti', 'id');
            $this->addForeignKey('fk_documenti_allegati_filemanager_mediafile1_idx', $this->tabella, 'filemanager_mediafile_id', 'filemanager_mediafile', 'id');
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste già";
        }

        return true;
    }

    /**
     * @inheritdoc
     */
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
