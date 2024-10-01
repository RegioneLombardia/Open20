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
class m220216_095500_create_partnership_profiles_category_mm extends AmosMigrationTableCreation
{
    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%partnership_profiles_category_mm%}}';
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
            'partnership_profiles_id' => $this->integer()->null()->defaultValue(null)->comment('Partnership profile'),
            'partnership_profiles_category_id' => $this->integer()->null()->defaultValue(null)->comment('Category'),
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
        $this->addForeignKey('fk_partnership_profiles_category_mm_category_id1','partnership_profiles_category_mm', 'partnership_profiles_category_id', 'partnership_profiles_category', 'id');
        $this->addForeignKey('fk_partnership_profiles_category_mm_proposal_id1','partnership_profiles_category_mm', 'partnership_profiles_id', 'partnership_profiles', 'id');
    }
}
