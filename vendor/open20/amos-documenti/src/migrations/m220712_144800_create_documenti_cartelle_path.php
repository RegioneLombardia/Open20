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
 * Class m201105_163600_create_documenti_agid_type
 */
class m220712_144800_create_documenti_cartelle_path extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%documenti_cartelle_path%}}';
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
            'id_doc_folder' => $this->integer()->null()->defaultValue(null)->comment('id_doc_folder'),
            'level' => $this->integer()->null()->defaultValue(null)->comment('level'),
            'id_folder' => $this->integer()->null()->defaultValue(null)->comment('id_folder'),
        ];
    }


    /**
     * Timestamp
     */
    protected function beforeTableCreation() {
        
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
    
     protected function addForeignKeys()
    {
        $this->addForeignKey('fk_id_doc_folder_documenti', $this->getRawTableName(), 'id_doc_folder', '{{%documenti}}', 'id');
        $this->addForeignKey('fk_id_folder_documenti', $this->getRawTableName(), 'id_folder', '{{%documenti}}', 'id');
    }
}
