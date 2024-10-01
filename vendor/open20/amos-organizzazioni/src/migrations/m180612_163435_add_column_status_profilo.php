<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\organizzazioni\models\Profilo;
use yii\db\Migration;

/**
 * Class m180612_163435_add_column_status_profilo
 */
class m180612_163435_add_column_status_profilo extends Migration
{
    private $tableName = '';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->tableName = Profilo::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->db->getTableSchema($this->tableName)->getColumn('status')) {
            $this->addColumn($this->tableName, 'status', $this->string(255)->defaultValue(null)->comment('Stato')->after('id'));
        }
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->db->getTableSchema($this->tableName)->getColumn('status')) {
            $this->dropColumn($this->tableName, 'status');
        }
    }
}
