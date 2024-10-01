<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m201117_165500_create_agid_dataset_table
 */
class m201117_165500_create_agid_dataset_table extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%agid_dataset%}}';
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

            // FK
            'agid_dataset_content_type_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Content Type'),
            'agid_dataset_type_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Type Organizational'),

            // COLUMNS
            'title' => $this->string()->null()->defaultValue(null)->comment('Title'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Description'),

            // workflow status
            'status' => $this->string()->null()->defaultValue(null)->comment('Workflow Status'),
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
     * Foreign Key
     *
     * @return void
     */
    protected function addForeignKeys() {

        // FK
        $this->addForeignKey('fk_agid_dataset_content_type_id', $this->tableName, 'agid_dataset_content_type_id', 'agid_dataset_content_type', 'id');
        $this->addForeignKey('fk_agid_dataset_type_id', $this->tableName, 'agid_dataset_type_id', 'agid_dataset_type', 'id');
    }
    
}
