<?php
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m221125_171052_create_table_onlyoffice_files
 */
class m221125_171052_create_table_onlyoffice_files extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%onlyoffice_files}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'attach_file_id' => $this->integer(11)->defaultValue(null)->null(),
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
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
    }
}