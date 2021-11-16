<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200424_181208_create_table_newsletter
 */
class m200424_181208_create_table_newsletter extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%notification_newsletter}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)->notNull()->comment('Status'),
            'subject' => $this->string(255)->notNull()->comment('Subject'),
            'send_date_begin' => $this->dateTime()->null()->defaultValue(null)->comment('Send date begin'),
            'send_date_end' => $this->dateTime()->null()->defaultValue(null)->comment('Send date end'),
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
}
