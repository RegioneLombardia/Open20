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
 * Class m170315_121534_create_table_event
 */
class m170315_121534_create_table_event extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)->defaultValue(null)->comment('Status'),
            'title' => $this->string(255)->notNull()->comment('Title'),
            'summary' => $this->text()->null()->comment('Summary'),
            'description' => $this->text()->null()->comment('Description'),
            'begin_date_hour' => $this->dateTime()->notNull()->comment('Begin Date And Hour'),
            'length' => $this->smallInteger()->null()->comment('Length'),
            'end_date_hour' => $this->dateTime()->null()->comment('End Date And Hour'),
            'publication_date_begin' => $this->date()->null()->comment('Publication Date Begin'),
            'publication_date_end' => $this->date()->null()->comment('Publication Date End'),
            'registration_limit_date' => $this->date()->null()->comment('Registration Limit Date'),
            'event_location' => $this->string(255)->defaultValue(null)->comment('Event Location'),
            'event_address' => $this->string(255)->defaultValue(null)->comment('Event Address'),
            'event_address_house_number' => $this->string(255)->defaultValue(null)->comment('Event Address House Number'),
            'event_address_cap' => $this->string(255)->defaultValue(null)->comment('Event Address Cap'),
            'seats_available' => $this->integer()->defaultValue(null)->comment('Seats Available'),
            'paid_event' => $this->boolean()->defaultValue(0)->comment('Paid Event'),
            'publish_in_the_calendar' => $this->boolean()->defaultValue(1)->comment('Publish In The Calendar'),
            'visible_in_the_calendar' => $this->boolean()->defaultValue(0)->comment('Visible In The Calendar'),
            'event_commentable' => $this->boolean()->notNull()->defaultValue(0)->comment('Event Commentable'),
            'event_management' => $this->boolean()->notNull()->defaultValue(0)->comment('Event Management'),
            'validated_at_least_once' => $this->boolean()->notNull()->defaultValue(0)->comment('Validated At Least Once'),
            'city_location_id' => $this->integer(11)->defaultValue(null)->comment('City Location ID'),
            'province_location_id' => $this->integer(11)->defaultValue(null)->comment('Province Location ID'),
            'country_location_id' => $this->integer(11)->defaultValue(null)->comment('Country Location ID'),
            'event_membership_type_id' => $this->integer()->null()->comment('Event Membership Type ID'),
            'length_mu_id' => $this->integer()->null()->comment('Length Measurement Unit ID'),
            'event_type_id' => $this->integer()->notNull()->comment('Event Type ID'),
            'community_id' => $this->integer()->null()->comment('Community ID')
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
        $this->addForeignKey('fk_event_membership_type', $this->getRawTableName(), 'event_membership_type_id', '{{%event_membership_type}}', 'id');
        $this->addForeignKey('fk_event_length_measurement_unit', $this->getRawTableName(), 'length_mu_id', '{{%event_length_measurement_unit}}', 'id');
        $this->addForeignKey('fk_event_type', $this->getRawTableName(), 'event_type_id', '{{%event_type}}', 'id');
    }
}
