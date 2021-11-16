<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use yii\db\Migration;

/**
 * Class m190607_152618_add_discussion_close_discussion
 */
class m190607_152618_add_discussion_close_discussion extends Migration
{
    private 
        $tableName,
        $fieldName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->tableName = \open20\amos\discussioni\models\DiscussioniTopic::tableName();
        $this->fieldName = 'close_comment_thread';
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        
        $table = $this->db->getTableSchema($this->tableName);
        if (!isset($table->columns[$this->fieldName])) {
            $this->addColumn($this->tableName, $this->fieldName, $this->boolean()->notNull()->defaultValue(0)->comment('Close discussion'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->fieldName);
        return true;
    }
}
