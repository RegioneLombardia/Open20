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
 * Class m170717_080001_alter_table_amos_widgets_add_dashboard_visible
 */
class m170717_080001_alter_table_amos_widgets_add_dashboard_visible extends Migration
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
            $this->addColumn($this->tableName, 'dashboard_visible', Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '0' AFTER default_order");
        } catch (Exception $e) {
            echo "Errore durante la modifica della tabella " . $this->tableName . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        $this->update(self::TABLE, array('dashboard_visible' => 1), array('child_of' => NULL));

        return true;
    }

    /**
     * Use this instead of function down().
     */
    public function safeDown()
    {
        try {
            $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
            $this->dropColumn(self::TABLE, 'dashboard_visible');
            $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (Exception $e) {
            echo "Errore durante il revert della modifica della tabella " . $this->tableName . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }
}
