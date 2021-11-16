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
class m191003_121231_create_table_event_seats extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_seats}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull()->comment('Event'),
            'sector' => $this->string()->notNull()->comment('Sector'),
            'row' => $this->string()->notNull()->comment('Row'),
            'seat' => $this->string()->notNull()->comment('Seat'),
            'status' => $this->integer()->defaultValue(1)->comment('Stato: 1-Empty,2-Assigned,3-To be assigned,4-Reassigned'),

            'type_of_assigned_participant' => $this->integer()->defaultValue(null)->comment('Type'),
            'user_id' => $this->integer()->defaultValue(null)->comment('User'),
            'event_participant_companion_id' => $this->integer()->defaultValue(null)->comment('Companion/Member of a group'),

            'automatic' => $this->integer(1)->defaultValue(0)->comment('Automatic'),
            'available_for_groups' => $this->integer(1)->defaultValue(0)->comment('Available forgroups'),
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
        $this->addForeignKey('fk_event_seats_event_id1', $this->getRawTableName(), 'event_id', '{{%event}}', 'id');
        $this->addForeignKey('fk_event_seats_user_id1', $this->getRawTableName(), 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_event_seats_companion_id1', $this->getRawTableName(), 'event_participant_companion_id', '{{%event_participant_companion}}', 'id');
    }
}
