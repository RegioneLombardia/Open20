<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\migrations
 * @category   Migration
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Handles the creation of table `report`.
 */
class m170524_130641_create_report_type_table extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%report_type}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->comment('Report type name'),
            'description' => $this->text()->null()->comment('Report Type description')
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
}
