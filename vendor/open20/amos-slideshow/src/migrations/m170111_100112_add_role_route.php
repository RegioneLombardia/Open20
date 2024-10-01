<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Handles the creation for table `slideshow`.
 */
class m170111_100112_add_role_route extends Migration {

    const TABLE = '{{%slideshow_route}}';

    /**
     * Use this instead of function up().
     */
    public function safeUp() {

        if ($this->db->schema->getTableSchema(self::TABLE, true) !== null) {
            try {
                $this->execute("ALTER TABLE `slideshow_route` ADD COLUMN `role` VARCHAR(255) NULL DEFAULT 'TUTTI' COMMENT 'Ruolo che vedrà lo slideshow' AFTER `slideshow_id`;");
            } catch (Exception $e) {
                echo "Errore durante l'aggiunta della colonna role della tabella " . self::TABLE . "\n";
                echo $e->getMessage() . "\n";
                return false;
            }
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella " . self::TABLE . " non esiste \n";
        }

        return true;
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown() {
        echo "Nessun down previsto";
        return true;
    }

}
