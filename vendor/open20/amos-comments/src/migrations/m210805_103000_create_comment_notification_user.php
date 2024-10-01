<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m210805_103000_create_comment_notification_user
 */
class m210805_103000_create_comment_notification_user extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%comment_notification_users%}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null()->comment('User ID'),
            'context_model_class_name' => $this->string()->defaultValue(null),
            'context_model_id' => $this->integer()->defaultValue(null),
            'enable' => $this->boolean()->defaultValue(null),
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
    protected function addForeignKeys() {
        $this->addForeignKey('fk_comment_notification_users_user_id', $this->tableName, 'user_id', 'user', 'id');
    }
}
