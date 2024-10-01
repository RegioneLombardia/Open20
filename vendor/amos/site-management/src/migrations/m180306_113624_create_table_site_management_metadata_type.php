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
 * Class m180306_113624_create_table_site_management_metadata_type
 */
class m180306_113624_create_table_site_management_metadata_type extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%site_management_metadata_type}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'type' => $this->string(50)->null()->defaultValue(null)->comment('Type')
        ];
    }

    /**
     * @inheritdoc
     */
    protected function afterTableCreation()
    {
        $this->batchInsert($this->tableName, [
            'id',
            'type'
        ], [
            [
                1,
                'HTML'
            ],
            [
                2,
                'Open Graph (social)'
            ]
        ]);

        return true;
    }
}
