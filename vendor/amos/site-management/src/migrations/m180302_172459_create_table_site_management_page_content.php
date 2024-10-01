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
 * Class m180302_172459_create_table_site_management_page_content
 */
class m180302_172459_create_table_site_management_page_content extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%site_management_page_content}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'tag' => $this->string(100)->notNull()->unique()->comment('Tag'),
            'title' => $this->string(255)->null()->defaultValue(null)->comment('Title'),
            'content' => $this->text()->null()->defaultValue(null)->comment('Content')
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
