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
 * Class m211015_193700_create_agid_organizational_unit_profile_type_table
 */
class m211015_193700_create_agid_organizational_unit_profile_type_table extends AmosMigrationTableCreation {

    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%agid_organizational_unit_profile_type%}}';
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
            'name' => $this->string()->null()->defaultValue(null)->comment('Name Agid Organizational Unit Profile Type'),
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
            'name' => 'Pubblico',
        ]);
        $this->insert($this->tableName, [
            'name' => 'Riservato',
        ]);
    }
    
}
