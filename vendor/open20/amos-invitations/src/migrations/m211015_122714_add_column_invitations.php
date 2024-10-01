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
class m211015_122714_add_column_invitations extends Migration
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
        $this->addColumn($this->tableName, 'invite_accepted', $this->integer(1)->defaultValue(0)->after('send'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'invite_accepted');

        return true;
    }
}
