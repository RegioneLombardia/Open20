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
class m220316_112714_add_column_invitations_token extends Migration
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
        $this->addColumn($this->tableName, 'token_expire_date', $this->dateTime()->defaultValue(null)->after('invitation_user_id'));
        $this->addColumn($this->tableName, 'token', $this->string()->defaultValue(null)->after('invitation_user_id'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'token');
        $this->dropColumn($this->tableName, 'token_expire_date');

        return true;
    }
}
