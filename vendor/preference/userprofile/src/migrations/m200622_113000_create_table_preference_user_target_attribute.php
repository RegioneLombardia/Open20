`<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m200622_113000_create_table_preference_user_target_attribute
 */
class m200622_113000_create_table_preference_user_target_attribute extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%preference_user_target_attribute}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'email' => $this->string(256)->null()->comment('Email'),
            'validated_email_flag' => $this->boolean()->defaultValue(0)->comment('Validated email flag'),
            'phone' => $this->string(256)->null()->comment('Phone'),
            'validated_phone_flag' => $this->boolean()->defaultValue(0)->comment('Validated phone flag'),
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
    protected function afterTableCreation()
    {
       // Nothing to do
    }

}

