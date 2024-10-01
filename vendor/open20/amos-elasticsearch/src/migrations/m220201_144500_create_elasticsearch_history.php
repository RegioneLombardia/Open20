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
class m220201_144500_create_elasticsearch_history extends AmosMigrationTableCreation
{
    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName()
    {

        $this->tableName = '{{%elasticsearch_history%}}';
    }

    /**
     * set table fields
     *
     * @return void
     */
    protected function setTableFields()
    {

        $this->tableFields = [

            // PK
            'id' => $this->primaryKey(),
            // COLUMNS
            'search_text' => $this->string()->null()->defaultValue(null)->comment('Text'),
            'results' => $this->text()->null()->defaultValue(null)->comment('Result'),
            'tot_found' => $this->integer()->null()->defaultValue(null)->comment('Tot found'),
            'user_id' => $this->integer()->null()->defaultValue(null)->comment('User'),
        ];
    }

    /**
     * Timestamp
     */
    protected function beforeTableCreation()
    {

        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

    /**
     * Override to add foreign keys after table creation.
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_elasticsearch_history_user_id1', 'elasticsearch_history', 'user_id', 'user', 'id');
    }

    public function afterTableCreation()
    {
        $this->execute('ALTER TABLE `elasticsearch_history` MODIFY `results` LONGTEXT');
    }
}
