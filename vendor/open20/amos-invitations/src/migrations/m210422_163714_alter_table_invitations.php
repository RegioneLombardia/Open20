<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations\migrations
 * @category   CategoryName
 */

use open20\amos\invitations\models\Invitation;
use yii\db\Migration;

/**
 * Class m210422_163714_alter_table_invitations
 */
class m210422_163714_alter_table_invitations extends Migration
{
    private $tableName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->tableName = Invitation::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'context_model_id', $this->string(255)->null()->defaultValue(null));
        $this->addColumn($this->tableName, 'register_action', $this->string(255)->null()->defaultValue(null)->after('send'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'context_model_id', $this->integer()->null()->defaultValue(null));
        $this->dropColumn($this->tableName, 'register_action');
        return true;
    }
}
