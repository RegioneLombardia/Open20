<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m170315_121530_create_table_event_membership_type
 */
class m170315_121530_create_table_event_membership_type extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_membership_type}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Title')
        ];
    }

    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->insert($this->tableName, ['id' => 1, 'title' => 'Open']);
        $this->insert($this->tableName, ['id' => 2, 'title' => 'On invitation']);
    }
}
