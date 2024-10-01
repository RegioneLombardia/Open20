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
class m220315_152714_add_column_invitations_category extends Migration
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
        $this->addColumn($this->tableName, 'category', $this->string()->defaultValue(null)->after('context_model_id'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'category');

        return true;
    }
}
