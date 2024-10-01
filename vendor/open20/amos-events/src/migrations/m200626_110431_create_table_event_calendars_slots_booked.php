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
 * Class m170315_121506_create_table_event_type
 */
class m200626_110431_create_table_event_calendars_slots_booked extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_calendars_slots_booked}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'event_calendars_slots_id' => $this->integer()->notNull()->comment('Event'),
            'user_id' => $this->integer()->defaultValue(null)->comment('User'),
            'booked_at' => $this->date()->null()->comment('Booked at'),
            'affiliation' => $this->string(),
            'cellphone' => $this->string(),
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
        $this->addForeignKey('fk_event_calendars_slots_booked_user_id1', $this->getRawTableName(), 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_event_calendars_slots_booked_event_calendars_sid1', $this->getRawTableName(), 'event_calendars_slots_id', '{{%event_calendars_slots}}', 'id');
    }
}
