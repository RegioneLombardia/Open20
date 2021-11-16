<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

use yii\db\Migration;

class m201106_172000_create_agid_person_documenti_mm_table extends Migration
{

    public function up()
    {
        /**
         * create table MM agid_person_organizational_unit_mm
         * and add only columns for foreign key
         */
        $this->createTable('agid_service_documenti_mm', [

            // PK
            'id' => $this->primaryKey(),

            // COLUMNS field to be FK
            'documenti_id' => $this->integer()->null()->defaultValue(null)->comment('Documenti'),
            'agid_service_id' => $this->integer()->null()->defaultValue(null)->comment('Agid service'),

            // timestamp fields
            'created_at' => $this->dateTime()->defaultValue(null)->comment('Created at'),
            'updated_at' => $this->dateTime()->defaultValue(null)->comment('Updated at'),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Deleted at'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created at'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Deleted by'),
        ]);

        $this->addForeignKey('fk_agid_service_documenti', 'agid_service_documenti_mm', 'agid_service_id', 'agid_service', 'id');
        $this->addForeignKey('fk_documenti_agid_service', 'agid_service_documenti_mm', 'documenti_id', 'documenti', 'id');
    }


    public function down()
    {

       $this->dropTable('agid_service_documenti_unit_mm');
    }
}
