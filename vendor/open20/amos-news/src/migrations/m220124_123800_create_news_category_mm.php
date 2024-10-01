<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m210112_154000_create_news_groups_table
 */
class m220124_123800_create_news_category_mm extends AmosMigrationTableCreation
{
    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%news_categorie_mm%}}';
    }

    /**
     * set table fields
     *
     * @return void
     */
    protected function setTableFields() {

        $this->tableFields = [

            // PK
            'id' => $this->primaryKey(),
            // COLUMNS
            'news_id' => $this->integer()->null()->defaultValue(null)->comment('News'),
            'news_categorie_id' => $this->integer()->null()->defaultValue(null)->comment('Category'),
        ];
    }

    /**
     * Timestamp
     */
    protected function beforeTableCreation() {
        
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

    /**
     * Override to add foreign keys after table creation.
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_news_categorie_mm_news_id1','news_categorie_mm', 'news_id', 'news', 'id');
        $this->addForeignKey('fk_news_categorie_mm_news_categorie_id1','news_categorie_mm', 'news_categorie_id', 'news_categorie', 'id');
    }
}
