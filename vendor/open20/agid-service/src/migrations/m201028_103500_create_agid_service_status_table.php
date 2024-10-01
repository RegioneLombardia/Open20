<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\service
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m201028_103500_create_agid_service_status_table
 */
class m201028_103500_create_agid_service_status_table extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%agid_service_status%}}';
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
            'name' => 'Attivo',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Non attivo',
        ]);
        
    }
    
}
