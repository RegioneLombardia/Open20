<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m201115_191118_create_table_agid_event_documents_mm
 */
class m201115_191118_create_table_agid_event_documents_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%agid_event_documents_mm}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull(),
            'document_id' => $this->integer()->notNull(),
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
