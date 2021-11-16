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
 * Class m170315_121500_create_table_event_type_context
 */
class m170315_121500_create_table_event_type_context extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_type_context}}';
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
        ];
    }

    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->insert($this->tableName, ['id' => 1, 'title' => 'Generic (not defined)', 'description' => '"Not defined" for events of other type (meeting, activity, deadlines, workshop, conferences, etc)']);
        $this->insert($this->tableName, ['id' => 2, 'title' => 'Project', 'description' => '"Project" for event of a project']);
        $this->insert($this->tableName, ['id' => 3, 'title' => 'Matchmaking', 'description' => '"Matchmaking" for event of matchmaking']);
    }
}
