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
 * Class m201027_213100_create_agid_organizational_unit_table
 */
class m201027_213100_create_agid_organizational_unit_table extends AmosMigrationTableCreation {

    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {
        $this->tableName = '{{%agid_organizational_unit%}}';
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
            'agid_organizational_unit_content_type_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Content Type'),
            'agid_organizational_unit_type_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Type Organizational'),
            
            // COLUMNS
            'name' => $this->string()->null()->defaultValue(null)->comment('Name'),
            'short_description' => $this->text(160)->null()->defaultValue(null)->comment('Short Description'),
            'skills' => $this->text()->null()->defaultValue(null)->comment('Skills'),
            'headquarters_name' => $this->text()->null()->defaultValue(null)->comment('Headquarters Name'),
            'address' => $this->text()->null()->defaultValue(null)->comment('Headquarters Address'),
            'public_hours' => $this->text()->null()->defaultValue(null)->comment('Hours for the public'),
            'cap' => $this->string(50)->null()->defaultValue(null)->comment('CAP'),
            'telephone_reference' => $this->text()->null()->defaultValue(null)->comment('Telephone Reference'),
            'mail_reference' => $this->text()->null()->defaultValue(null)->comment('Mail Reference'),
            'pec_reference' => $this->text()->null()->defaultValue(null)->comment('PEC Reference'),
            'further_information' => $this->text()->null()->defaultValue(null)->comment('Further Information'),
            'help_box' => $this->text()->null()->defaultValue(null)->comment("Help Box"),

            // workflow status
            'status' => $this->string()->null()->defaultValue(null)->comment('Workflow Status'),
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
     * Foreign Key
     *
     * @return void
     */
    protected function addForeignKeys() {

        // FK
        $this->addForeignKey('fk_agid_organizational_unit_type', $this->tableName, 'agid_organizational_unit_type_id', 'agid_organizational_unit_type', 'id');
        $this->addForeignKey('fk_agid_organizational_unit_content_type', $this->tableName, 'agid_organizational_unit_content_type_id', 'agid_organizational_unit_content_type', 'id');
    }
}
