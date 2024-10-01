<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;


class m201027_202800_create_agid_organizational_unit_service_mm_table extends Migration
{

    public function up()
    {
        /**
         * create table MM agid_organizational_unit_service_mm
         * and add only columns for foreign key
         */
        $this->createTable('agid_organizational_unit_service_mm', [

            // PK
            'id' => $this->primaryKey(),

            // COLUMNS field to be FK
            'agid_organizational_unit_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Organizational Unit'),
            'agid_service_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Service'),

            // TIMESTAMP fields
            'created_at' => $this->dateTime()->defaultValue(null)->comment('Created at'),
            'updated_at' => $this->dateTime()->defaultValue(null)->comment('Updated at'),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Deleted at'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created at'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Deleted by'),
        ]);
    }


    public function down()
    {

       // Drop Table agid_organizational_unit_service_mm
       $this->dropTable('agid_organizational_unit_service_mm');
    }
}
