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
 * Class m171212_090002_truncate_mm
 */
class m171212_090002_truncate_mm extends Migration
{
    const TABLE = '{{%amos_user_dashboards_widget_mm}}';
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
            $this->execute("SET FOREIGN_KEY_CHECKS=0;");
            $this->execute("TRUNCATE ".self::TABLE.";");
            $this->execute("SET FOREIGN_KEY_CHECKS=1;");
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
            echo "Nessun down disponibile";
        } catch (Exception $e) {
            echo "Errore durante il revert della modifica della tabella " . $this->tableName . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }
}
