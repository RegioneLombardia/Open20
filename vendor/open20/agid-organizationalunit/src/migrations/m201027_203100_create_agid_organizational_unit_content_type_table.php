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
 * Class m201027_203100_create_agid_organizational_unit_content_type_table
 */
class m201027_203100_create_agid_organizational_unit_content_type_table extends AmosMigrationTableCreation {

    
    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%agid_organizational_unit_content_type%}}';
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

        $this->insert('agid_organizational_unit_content_type', [
            'name' => 'Organi di governo',
        ]);

        $this->insert('agid_organizational_unit_content_type', [
            'name' => 'Aree amministrative',
        ]);

        $this->insert('agid_organizational_unit_content_type', [
            'name' => 'Uffici',
        ]);

        $this->insert('agid_organizational_unit_content_type', [
            'name' => 'Enti e fondazioni',
        ]);
    }
}
