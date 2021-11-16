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
class m201105_163600_create_documenti_agid_type extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%documenti_agid_type%}}';
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

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti albo pretorio',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Modulistica',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti funzionamento interno',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Atti normativi',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Accordi tra enti',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti attività politica',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti (tecnici) di supporto',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Istanze',
        ]);
    }
    
}
