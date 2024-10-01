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
 * Class m201110_164936_create_table_agid_event_typology
 */
class m201110_164936_create_table_agid_event_typology extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%agid_event_typology}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Name'),
            'level' => $this->smallInteger()->unsigned()->notNull()->comment('Level'),
            'parent_id' => $this->smallInteger()->unsigned()->null()->defaultValue(null)->comment('Parent Id'),
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
