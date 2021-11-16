<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\seo\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m180703_161534_create_table_seo_data
 */
class m180703_161534_create_table_seo_data extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%seo_data}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'classname' => $this->string(255)->null()->comment('Content Class Name'),
            'content_id' => $this->integer(11)->comment('Table Content id'),
            'pretty_url' => $this->string(255)->null()->unique()->comment('Seo Pretty Url'),
            'meta_title' => $this->text()->null(),
            'meta_description' => $this->text()->null(),
            'meta_keywords' => $this->text()->null(),
            'og_title' => $this->text()->null(),
            'og_description' => $this->text()->null(),
            'og_type' => $this->string(255)->null(),
            'meta_robots' => $this->string(255)->null(),
            'meta_googlebot' => $this->string(255)->null(),
            'unavailable_after_date' => $this->dateTime()->null()->comment('Begin Date And Hour'),
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

    
    protected function afterForeignKeysAdd() {
        $this->createIndex(
            'idx-classname-content_id',
            $this->tableName,
            'classname,content_id',
            true //unique
        );
    }
}
