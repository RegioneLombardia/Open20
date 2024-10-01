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
class m161103_150112_create_userflag_table extends Migration {

    const TABLE = '{{%slideshow_userflag}}';

    /**
     * Use this instead of function up().
     */
    public function safeUp() {
       
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            try {
                $this->createTable(self::TABLE, [
                    'id' => $this->primaryKey(),
                    'user_id' => $this->integer()->notNull(),
                    'slideshow_route_id' => $this->integer()->notNull(),
                    'created_at' => $this->dateTime()->null()->defaultValue(null),
                    'updated_at' => $this->dateTime()->null()->defaultValue(null),
                    'deleted_at' => $this->dateTime()->null()->defaultValue(null),
                    'created_by' => $this->integer()->null()->defaultValue(null),
                    'updated_by' => $this->integer()->null()->defaultValue(null),
                    'deleted_by' => $this->integer()->null()->defaultValue(null),
                        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);

                $this->addForeignKey('fk_slideshow_userflag_slideshow_route_id1', self::TABLE, 'slideshow_route_id', 'slideshow_route', 'id');
                $this->addForeignKey('fk_slideshow_userflag_user_id', self::TABLE, 'user_id', 'user', 'id');
            } catch (Exception $e) {
                echo "Errore durante la creazione della tabella " . self::TABLE . "\n";
                echo $e->getMessage() . "\n";
                return false;
            }
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella " . self::TABLE . " esiste gia'\n";
        }

        return true;
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown() {
        try {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            $this->dropTable(self::TABLE);
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (Exception $e) {
            echo "Errore durante la cancellazione della tabella " . self::TABLE . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }

}
