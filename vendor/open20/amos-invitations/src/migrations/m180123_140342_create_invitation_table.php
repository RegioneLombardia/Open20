<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `invitation`.
 */
class m180123_140342_create_invitation_table extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%invitation}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->defaultValue(null)->comment('Name'),
            'surname' => $this->string(255)->defaultValue(null)->comment('Surname'),
            'message' => $this->text()->defaultValue(null)->comment('Message'),
            'send_time' => $this->dateTime()->defaultValue(null)->comment('Time to send invitation'),
            'send' => $this->boolean()->defaultValue(null)->comment('This notification was sent?'),
            'invitation_user_id' => $this->integer(11)->notNull()->comment('Person to invitate'),
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
        $this->addForeignKey('fk_invitation_invitation_user_1', $this->getRawTableName(),'invitation_user_id', '{{%invitation_user}}', 'id');
    }

}