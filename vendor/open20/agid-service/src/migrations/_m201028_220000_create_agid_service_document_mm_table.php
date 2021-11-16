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
 * Class m201028_220000_create_agid_service_document_mm_table
 */
class m201028_220000_create_agid_service_document_mm_table extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%agid_service_document_mm%}}';
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
            'agid_document_id' => $this->integer()->null()->defaultValue(null)->comment('Document'),
            'agid_service_id' => $this->integer()->null()->defaultValue(null)->comment('Service'),
        ];
    }


    /**
     * Timestamp
     *
     * @return void
     */
    protected function beforeTableCreation() {

        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }


    /**
     * Add Foreign Key
     *
     * @return void
     */
    protected function addForeignKeys() {

        // FK
        $this->addForeignKey('fk_agid_document_service', $this->tableName, 'agid_document_id', 'agid_document', 'id');
        $this->addForeignKey('fk_agid_service_document', $this->tableName, 'agid_service_id', 'agid_service', 'id');
    }
    
}
