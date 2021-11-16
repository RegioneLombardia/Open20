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
 * Class m210217_105200_create_documenti_agid_type_roles
 */
class m210217_105200_create_documenti_agid_type_roles extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%documenti_agid_type_roles}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'documenti_agid_type_id' => $this->integer()->notNull()->comment('Documenti agid type ID'),
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
        $this->addForeignKey('fk_documenti_agid_type__roletype', $this->getRawTableName(),
            'documenti_agid_type_id', '{{%documenti_agid_type}}', 'id');
        $this->addForeignKey('fk_documenti_agid_type__roleuser', $this->getRawTableName(),
            'user_id', '{{%user}}', 'id');
    }

}

