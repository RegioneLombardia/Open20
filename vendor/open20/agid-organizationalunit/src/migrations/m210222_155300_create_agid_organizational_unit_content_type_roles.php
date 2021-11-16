<?php

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
 * Class m210222_155300_create_agid_organizational_unit_content_type_roles
 */
class m210222_155300_create_agid_organizational_unit_content_type_roles extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%agid_organizational_unit_content_type_roles}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'agid_organizational_unit_content_type_id' => $this->integer()->notNull()->comment('organizational_unit content type ID'),
            'user_id' => $this->integer()->notNull()->comment('User ID'),
            'role' => $this->string()->comment('role')
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
        $this->addForeignKey('fk_ou_agid_content_type__roletype', $this->getRawTableName(),
            'agid_organizational_unit_content_type_id', '{{%agid_organizational_unit_content_type}}', 'id');
        $this->addForeignKey('fk_ou_agid_content_type__roleuser', $this->getRawTableName(),
            'user_id', '{{%user}}', 'id');
    }

}

