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
 * Class m170315_121506_create_table_event_type
 */
class m170315_121506_create_table_event_type extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_type}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Title'),
            'description' => $this->text()->notNull()->comment('Description'),
            'color' => $this->string(255)->notNull()->comment('Color'),
            'locationRequested' => $this->boolean()->notNull()->defaultValue(0)->comment('Location Requested'),
            'durationRequested' => $this->boolean()->notNull()->defaultValue(0)->comment('Duration Requested'),
            'logoRequested' => $this->boolean()->notNull()->defaultValue(0)->comment('Logo Requested'),
            'event_context_id' => $this->integer()->notNull()->comment('Event Context ID')
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
        $this->addForeignKey('fk_event_type_event_type_context', $this->getRawTableName(), 'event_context_id', '{{%event_type_context}}', 'id');
    }
}
