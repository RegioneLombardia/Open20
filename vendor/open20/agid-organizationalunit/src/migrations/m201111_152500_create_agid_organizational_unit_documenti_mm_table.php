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


class m201111_152500_create_agid_organizational_unit_documenti_mm_table extends Migration
{

    public function up()
    {
        $this->createTable('agid_organizational_unit_documenti_mm', [

            // PK
            'id' => $this->primaryKey(),

            // COLUMNS field to be FK
            'agid_organizational_unit_id' => $this->integer()->null()->defaultValue(null)->comment('Agid Organizational Unit'),
            'documenti_id' => $this->integer()->null()->defaultValue(null)->comment('Documenti'),

            // TIMESTAMP fields
            'created_at' => $this->dateTime()->defaultValue(null)->comment('Created at'),
            'updated_at' => $this->dateTime()->defaultValue(null)->comment('Updated at'),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Deleted at'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created at'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Deleted by'),

                 
    
        ]);

        // addForeignKey
        $this->addForeignKey(
            'fk-documenti-agid-organizational-unit-id',
            'agid_organizational_unit_documenti_mm',
            'agid_organizational_unit_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );

        // addForeignKey
        $this->addForeignKey(
            'fk-documenti-id',
            'agid_organizational_unit_documenti_mm',
            'documenti_id',
            'documenti',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {

       // Drop Table 
       $this->dropTable('agid_organizational_unit_documenti_mm');
    }
}
