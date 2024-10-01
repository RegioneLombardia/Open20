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
 * Class m201028_103000_create_agid_type_service_table
 */
class m201028_103000_create_agid_service_type_table extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%agid_service_type%}}';
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
            'name' => 'Anagrafe e stato civile',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Cultura e tempo libero',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Vita lavorativa',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Attività produttive e commercio',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Appalti pubblici',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Catasto e urbanistica => Urbanistica e edilizia',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Turismo',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Mobilità e trasporti',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Educazione e formazione',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Giustizia e sicurezza pubblica',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Tributi e finanze',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Ambiente',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Salute, benessere e assistenza',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Autorizzazioni',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Agricoltura',
        ]);
    }
    
}
