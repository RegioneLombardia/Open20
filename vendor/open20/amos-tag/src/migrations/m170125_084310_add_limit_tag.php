<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

class m170125_084310_add_limit_tag extends \yii\db\Migration
{
    const TABLE = '{{%tag}}';
    private $tableName;

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
            $this->db->createCommand()->setSql("ALTER TABLE " . $this->tableName . " ADD limit_selected_tag integer(11) DEFAULT NULL AFTER descrizione")->execute();
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
            $this->dropColumn(self::TABLE, 'limit_selected_tag');
        } catch (Exception $e) {
            echo "Errore durante il revert della modifica della tabella " . $this->tableName . "\n";
            echo $e->getMessage() . "\n";
            return false;
        }
        return true;
    }
}
