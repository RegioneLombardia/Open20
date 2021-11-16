<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m170807_154117_create_user_lockout
 */
class m170807_154117_create_user_lockout extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%user_lockout}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'attempts' => $this->string(11)->defaultValue(null)->comment('Number of attempts'),
            'user_id' => $this->integer(11)->defaultValue(null)->comment('User ID'),
            'ip' => $this->string()->defaultValue(null)->comment('IP Address')
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
    
    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_user_lockout_user', $this->getRawTableName(), 'user_id', 'user', 'id');
    }
}
