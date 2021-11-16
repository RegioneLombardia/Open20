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
class m200123_181331_create_table_event_calendars_slots extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_calendars_slots}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'event_calendars_id' => $this->integer()->notNull()->comment('Event'),
            'date' => $this->date()->notNull()->comment('Date'),
            'hour_start' => $this->time()->notNull()->comment('Time start'),
            'hour_end' => $this->time()->defaultValue(null)->comment('Time end'),
            'user_id' => $this->integer()->defaultValue(null)->comment('User'),
            'booked_at' => $this->date()->null()->comment('Booked at'),
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
        $this->addForeignKey('fk_event_calendars_slots_user_id1', $this->getRawTableName(), 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_event_calendars_slots_event_calendars_id1', $this->getRawTableName(), 'event_calendars_id', '{{%event_calendars}}', 'id');
    }
}
