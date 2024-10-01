<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m180306_112004_create_table_site_management_metadata
 */
class m180306_112004_create_table_site_management_metadata extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%site_management_metadata}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'key_value' => $this->string(255)->null()->defaultValue(null)->comment('Key Value'),
            'content' => $this->text()->null()->defaultValue(null)->comment('Content'),
            'metadata_type_id' => $this->integer()->null()->defaultValue(null)->comment('Metadata Type ID')
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
