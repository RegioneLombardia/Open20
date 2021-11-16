<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfileTitoliStudio;
use yii\db\Migration;

class m160912_092428_create_titoli_studio extends Migration
{    
    private $tabella = null;

    public function __construct()
    {
        $this->tabella = UserProfileTitoliStudio::tableName();
        parent::__construct();
    }
    
    public function safeUp()
    {
        $this->createTable($this->tabella, [
            'id' => $this->primaryKey(11),
            'denominazione' => $this->string(255)->defaultValue(null)->comment('Denominazione'),
            'descrizione' => $this->text()->comment('Descrizione'),
            'created_at' => $this->dateTime()->defaultValue(null)->comment('Creato il'),
            'updated_at' => $this->dateTime()->defaultValue(null)->comment('Aggiornato il'),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da'),
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        // Aggiungo i campi standard
        $this->batchInsert($this->tabella,['denominazione'], [
            ['Nessun titolo / Licenza elementare'],
            ['Licenza media'],
            ['Qualifica professionale'],
            ['Diploma di maturità'],
            ['Laurea / Mater post Laurea'],
        ]);
        return true;
    }

    public function safeDown()
    {
        $this->dropTable($this->tabella);
        return true;
    }
    
}
