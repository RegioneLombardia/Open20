<?php
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m210726_101600_user_profile_validation_notify
 */
class m210726_101600_user_profile_validation_notify extends AmosMigrationTableCreation
{
    protected function setTableName()
    {
        $this->tableName = '{{%user_profile_validation_notify}}';
    }
    
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'status' => $this->boolean()->notNull()->defaultValue(0)->comment('Status'),
            'user_id' => $this->integer()->notNull()->comment('User profile'),
        ];
    }
    
    
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
}
