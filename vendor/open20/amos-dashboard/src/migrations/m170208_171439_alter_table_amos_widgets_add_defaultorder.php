<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m170208_171439_alter_table_amos_widgets_add_defaultorder
 */
class m170208_171439_alter_table_amos_widgets_add_defaultorder extends Migration
{
    const TABLE = '{{%amos_widgets}}';
    private $tableName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = $this->db->getSchema()->getRawTableName(self::TABLE);
    }

    /**
     * Use this instead of function up().
     */
    public function safeUp()
    {
        try {
            $this->addColumn($this->tableName, 'default_order', Schema::TYPE_SMALLINT . " NOT NULL DEFAULT '1' AFTER child_of");
        } catch (Exception $e) {
            echo "Errore durante la modifica della tabella " . $this->tableName . "\n";
            echo $e->getMessage() . "\n";
            return false;
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
            $this->dropColumn(self::TABLE, 'default_order');
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (Exception $e) {
            echo "Errore durante il revert della modifica della tabella " . $this->tableName . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }
}
