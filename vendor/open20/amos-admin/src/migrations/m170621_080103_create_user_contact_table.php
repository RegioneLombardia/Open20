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
 * Handles the creation of table `user_contact`.
 */
class m170621_080103_create_user_contact_table extends AmosMigrationTableCreation
{

    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%user_contact}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('User ID'),
            'contact_id' => $this->integer()->notNull()->comment('Contact user ID'),
            'status' => $this->string(100)->notNull()->comment('Status'),
            'accepted_at' =>  $this->dateTime()->null()->defaultValue(null)->comment('Accepted at'),
            'reminders_count' => $this->smallInteger()->null()->defaultValue(0)->comment('Number of reminders'),
            'last_reminder_at' => $this->dateTime()->null()->defaultValue(null)->comment('Last reminder sent at')
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
        $this->addForeignKey('fk_user_contact_user', $this->tableName, 'user_id', 'user', 'id');
        $this->addForeignKey('fk_user_contact_contact', $this->tableName, 'contact_id', 'user', 'id');
    }
}
