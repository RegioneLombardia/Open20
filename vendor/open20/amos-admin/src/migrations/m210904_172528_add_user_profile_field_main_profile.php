<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use yii\db\Migration;

/**
 * Class m210904_172528_add_user_profile_field_main_profile
 */
class m210904_172528_add_user_profile_field_main_profile extends Migration
{
    private $tableName;
    private $fieldName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->tableName = UserProfile::tableName();
        $this->fieldName = 'main_user_profile_id';
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->fieldName, $this->integer()->null()->defaultValue(null)->after('attivo'));
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
