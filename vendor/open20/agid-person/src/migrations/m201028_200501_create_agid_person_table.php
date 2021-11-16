<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m201028_200500_create_agid_service_table
 */
class m201028_200501_create_agid_person_table extends AmosMigrationTableCreation {

    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {
        $this->tableName = '{{%agid_person%}}';
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
            'agid_person_content_type_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Person Content Type'),
            'agid_person_type_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Person Type'),



            // Allegati FK
            'agid_document_cv_id' => $this->integer()->null()->defaultValue(null)->comment('Curriculum Vitae'),
            'agid_document_import_id' => $this->integer()->null()->defaultValue(null)->comment('Importi di viaggio e/o servizio'),
            'agid_document_other_posts_id' => $this->integer()->null()->defaultValue(null)->comment('Altre cariche'),
            'agid_document_nomination_id' => $this->integer()->null()->defaultValue(null)->comment('Atto di nomina'),
            'agid_document_balance_sheet_id' => $this->integer()->null()->defaultValue(null)->comment('Situazione patrimoniale'),
            'agid_document_tax_return_id' => $this->integer()->null()->defaultValue(null)->comment('Dichiarazione dei redditi'),
            'agid_document_election_expenses_id' => $this->integer()->null()->defaultValue(null)->comment('Spese elettorali'),
            'agid_document_changes_balance_sheet_id' => $this->integer()->null()->defaultValue(null)->comment('Variazioni situazione patrimoniale'),

            //campi
            'name' => $this->string()->null()->defaultValue(null)->comment('Nome'),
            'surname' => $this->string()->null()->defaultValue(null)->comment('Cognome'),
            'role' => $this->string()->null()->defaultValue(null)->comment('Ruolo'),
            'role_description' => $this->text()->null()->defaultValue(null)->comment('Descrizione ruolo'),
            'date_end_assignment' => $this->dateTime()->null()->defaultValue(null)->comment('Data conclusione incarico'),
            'skills' => $this->text()->null()->defaultValue(null)->comment('Competenze'),
            'delegation' => $this->text()->null()->defaultValue(null)->comment('Deleghe'),
            'date_start_settlement' => $this->dateTime()->null()->defaultValue(null)->comment('Data insediamento'),
            'bio' => $this->text()->null()->defaultValue(null)->comment('Biografia'),
            'telephone' => $this->string()->null()->defaultValue(null)->comment('Numero di telefono'),
            'email' => $this->string()->null()->defaultValue(null)->comment('Indirizzo email'),
            'other_info' => $this->text()->null()->defaultValue(null)->comment('Ulteriori informazioni'),

            
            // workflow status
            'status' => $this->string()->null()->defaultValue(null),
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
        $this->addForeignKey('fk_agid_person_content_type', $this->tableName, 'agid_person_content_type_id', 'agid_person_content_type', 'id');
        $this->addForeignKey('fk_agid_person_type', $this->tableName, 'agid_person_type_id', 'agid_person_type', 'id');

        // TODO FK per Documenti
        // $this->addForeignKey('fk_agid_document_cv_id', '{{%agid_person%}}', 'agid_document_cv_id', 'agid_documento', 'id');
        // $this->addForeignKey('fk_agid_document_import_id', '{{%agid_person%}}', 'agid_document_import_id', 'agid_documento', 'id');
        // $this->addForeignKey('fk_agid_document_other_posts_id', '{{%agid_person%}}', 'agid_document_other_posts_id', 'agid_documento', 'id');
        // $this->addForeignKey('fk_agid_document_nomination_id', '{{%agid_person%}}', 'agid_document_nomination_id', 'agid_documento', 'id');
        // $this->addForeignKey('fk_agid_document_balance_sheet_id', '{{%agid_person%}}', 'agid_document_balance_sheet_id', 'agid_documento', 'id');
        // $this->addForeignKey('fk_agid_document_tax_return_id', '{{%agid_person%}}', 'agid_document_tax_return_id', 'agid_documento', 'id');
        // $this->addForeignKey('fk_agid_document_election_expenses_id', '{{%agid_person%}}', 'agid_document_election_expenses_id', 'agid_documento', 'id');
        // $this->addForeignKey('fk_agid_document_changes_balance_sheet_id', '{{%agid_person%}}', 'agid_document_changes_balance_sheet_id', 'agid_documento', 'id');

    }
}
