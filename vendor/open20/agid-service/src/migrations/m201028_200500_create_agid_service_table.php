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
class m201028_200500_create_agid_service_table extends AmosMigrationTableCreation {

    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {
        $this->tableName = '{{%agid_service%}}';
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
            'agid_content_type_service_id' => $this->integer()->null()->defaultValue(null)->comment('Service Content Type'),
            'agid_service_type_id' => $this->integer()->null()->defaultValue(null)->comment('Service Type'),
            'agid_service_status_id' => $this->integer()->null()->defaultValue(null)->comment('Service Status'),
            'agid_uo_manager_id' => $this->integer()->null()->defaultValue(null)->comment('Uff. Responsabile'),
            'agid_uo_area_id' => $this->integer()->null()->defaultValue(null)->comment('Area'),
            // ??! TODO FK per Documenti
            // 'agid_agid_documento_id' => $this->integer()->null()->defaultValue(null)->comment('FK Documento'),

            'name' => $this->string()->null()->defaultValue(null)->comment('Nome'),
            'service_status_motivation' => $this->text()->null()->defaultValue(null)->comment('Motivo dello stato'),
            'subtitle' => $this->text()->null()->defaultValue(null)->comment('Titolo alternativo/Sottotitolo'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Descrizione (abstract)'),
            'long_description' => $this->text()->null()->defaultValue(null)->comment('Descrizione estesa'),
            'recipients_description' => $this->text()->null()->defaultValue(null)->comment('Descrizione (abstract)'),
            'persons_apply' => $this->text()->null()->defaultValue(null)->comment('Chi può presentare'),
            'geographical_apply' => $this->text()->null()->defaultValue(null)->comment('Copertura geografica'),
            'procedure_apply' => $this->text()->null()->defaultValue(null)->comment('Come si fa'),
            'output' => $this->text()->null()->defaultValue(null)->comment('Output/Cosa si ottiene'),
            'outcome_procedure_apply' => $this->text()->null()->defaultValue(null)->comment('Procedure collegate all\'esito'),
            'digital_channel_url' => $this->text()->null()->defaultValue(null)->comment('Canale digitale'),
            'authentication_way' => $this->text()->null()->defaultValue(null)->comment('Autenticazione'),
            'physical_channel' => $this->text()->null()->defaultValue(null)->comment('Canale fisico'),
            'physical_channel_reservation' => $this->text()->null()->defaultValue(null)->comment('Canale fisico - prenotazione'),
            'instructions' => $this->text()->null()->defaultValue(null)->comment('Cosa serve (istruzioni per partecipare al servizio)'),
            'costs' => $this->text()->null()->defaultValue(null)->comment('Costi'),
            'constrains' => $this->text()->null()->defaultValue(null)->comment('Vincoli'),
            'phases_deadline' => $this->text()->null()->defaultValue(null)->comment('Fasi e scadenze'),
            'special_case' => $this->text()->null()->defaultValue(null)->comment('Casi particolari'),
            'external_links' => $this->text()->null()->defaultValue(null)->comment('Link a siti esterni'),
            
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
        $this->addForeignKey('fk_agid_content_type_service', $this->tableName, 'agid_content_type_service_id', 'agid_content_type_service', 'id');
        $this->addForeignKey('fk_agid_service_type', $this->tableName, 'agid_service_type_id', 'agid_service_type', 'id');
        $this->addForeignKey('fk_agid_service_status', $this->tableName, 'agid_service_status_id', 'agid_service_status', 'id');
        //$this->addForeignKey('fk_agid_uo_manager', $this->tableName, 'agid_uo_manager_id', 'agid_organizational_unit', 'id');
        //$this->addForeignKey('fk_agid_uo_area', $this->tableName, 'agid_uo_area_id', 'agid_organizational_unit', 'id');


        // TODO FK per Documenti
        // $this->addForeignKey('fk_agid_documento', $this->tableName, 'agid_agid_documento_id', 'agid_documento', 'id');
        

    }
}
