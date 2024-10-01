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


class m210224_171200_create_agid_service_organizational_unit_mm_table extends Migration
{

    public function up()
    {
  
        $this->createTable('agid_service_organizational_unit_mm', [

            // PK
            'id' => $this->primaryKey(),

            // COLUMNS field to be FK
            'agid_service_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Service'),
            'agid_organizational_unit_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Organizational Unit'),

            // TIMESTAMP fields
            'created_at' => $this->dateTime()->defaultValue(null)->comment('Created at'),
            'updated_at' => $this->dateTime()->defaultValue(null)->comment('Updated at'),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Deleted at'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created at'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Deleted by'),
        ]);


            
        // creates index for column agid_service_id
        $this->createIndex(
            'idx-agid_service_uo_mm-agid_service_id',
            'agid_service_organizational_unit_mm',
            'agid_service_id'
        );

        // creates index for column agid_service_id
        $this->createIndex(
            'idx-agid_service_uo_mm-agid_organizational_unit_id',
            'agid_service_organizational_unit_mm',
            'agid_organizational_unit_id'
        );


    }


    public function down()
    {

       // Drop Table agid_service_organizational_unit_mm
       $this->dropTable('agid_service_organizational_unit_mm');
    }
}
