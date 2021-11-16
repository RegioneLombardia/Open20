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
 * Class m201112_114900_create_news_content_type_table
 */
class m201112_114900_create_news_content_type_table extends AmosMigrationTableCreation
{
    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%news_content_type%}}';
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
            'name' => $this->string()->null()->defaultValue(null)->comment('Name'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Description'),
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
     * Insert default value
     *
     * @return void
     */
    protected function afterTableCreation(){

        $this->insert('news_content_type', [
            'name' => 'Novità',
            'name' => 'Avviso',
        ]);
    }
}
