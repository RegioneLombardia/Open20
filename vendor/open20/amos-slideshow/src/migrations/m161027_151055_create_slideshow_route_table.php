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
 * Handles the creation for table `slideshow_route`.
 */
class m161027_151055_create_slideshow_route_table extends Migration
{
    const TABLE = '{{%slideshow_route}}';

    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        $tableName = $this->db->getSchema()->getRawTableName(self::TABLE);

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null) {
            try {
                $this->createTable(self::TABLE, [
                    'id' => $this->primaryKey(),
                    'route' => $this->string(255)->notNull(),
                    'already_view' => $this->boolean(),
                    'slideshow_id' => $this->integer()->notNull(),
                    'created_at' => $this->dateTime()->null()->defaultValue(null),
                    'updated_at' => $this->dateTime()->null()->defaultValue(null),
                    'deleted_at' => $this->dateTime()->null()->defaultValue(null),
                    'created_by' => $this->integer()->null()->defaultValue(null),
                    'updated_by' => $this->integer()->null()->defaultValue(null),
                    'deleted_by' => $this->integer()->null()->defaultValue(null),
                ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
                $this->addForeignKey('fk_' . $tableName . '_slideshow_id', self::TABLE, 'slideshow_id', 'slideshow', 'id');
            } catch (Exception $e) {
                echo "Errore durante la creazione della tabella " . $tableName . "\n";
                echo $e->getMessage() . "\n";
                return false;
            }
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella " . $tableName . " esiste gia'\n";
        }

        return true;
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
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
