<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `{{%event_invitation}}`.
 */
class m181112_163318_create_event_invitation_table extends AmosMigrationTableCreation
{
    
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%event_invitation}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull(),
            'code' => $this->string(36)->null()->comment('UUID subsription code for external users'),
            'email' => $this->string(255),
            'fiscal_code' => $this->string(16),
            'name' => $this->string(16),
            'surname' => $this->string(16),
            'type' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Type of invited user - 1:registered, 2:imported'),
            'state' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('State of invitation - 1:invited, 2:accepted, 3:rejected'),
            'invitation_sent_on' => $this->dateTime()->null(),
            'invitation_response_on' => $this->dateTime()->null(),
            'user_id' => $this->integer()->null(),
            'partner_of' => $this->integer()->null(),
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
        $this->addForeignKey('fk_event_invitation_event', $this->getRawTableName(), 'event_id', '{{%event}}', 'id');
        $this->addForeignKey('fk_event_invitation_user', $this->getRawTableName(), 'user_id', '{{%user}}', 'id');
        $this->addForeignKey('fk_event_invitation_partner', $this->getRawTableName(), 'partner_of', '{{%user}}', 'id');
    }

}
