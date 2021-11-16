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
 * Class m170315_121525_create_table_event_length_measurement_unit
 */
class m170315_121525_create_table_event_length_measurement_unit extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_length_measurement_unit}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->comment('Title'),
            'date_interval_period' => $this->string()->notNull()->comment('Date Interval Period')
        ];
    }

    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->insert($this->tableName, ['id' => 1, 'title' => 'Hours', 'date_interval_period' => 'H']);
        $this->insert($this->tableName, ['id' => 2, 'title' => 'Days', 'date_interval_period' => 'D']);
    }
}
