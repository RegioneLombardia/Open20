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
 * Class m201027_203100_create_agid_content_type_table
 */
class m201028_103100_create_agid_content_type_service_table extends AmosMigrationTableCreation {

    
    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%agid_content_type_service%}}';
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
            'name' => $this->string()->null()->defaultValue(null)->comment('Nome'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Descrizione'),
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
     * Insert default value
     *
     * @return void
     */
    protected function afterTableCreation(){

        $this->insert($this->tableName, [
            'name' => 'Servizi',
        ]);

    }
}
