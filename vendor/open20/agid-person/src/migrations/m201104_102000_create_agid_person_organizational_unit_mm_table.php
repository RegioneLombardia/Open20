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

class m201104_102000_create_agid_person_organizational_unit_mm_table extends Migration
{

    public function up()
    {
        /**
         * create table MM agid_person_organizational_unit_mm
         * and add only columns for foreign key
         */
        $this->createTable('agid_person_organizational_unit_mm', [

            // PK
            'id' => $this->primaryKey(),

            // COLUMNS field to be FK
            'agid_organizational_unit_id' => $this->integer()->null()->defaultValue(null)->comment('Agis Organizational Unit'),
            'agid_person_id' => $this->integer()->null()->defaultValue(null)->comment('Agis Organizational Unit Father'),
            'role' => $this->string()->null()->defaultValue(null)->comment('Ruolo'),
            'function' => $this->text()->null()->defaultValue(null)->comment('Funzione'),

            // timestamp fields
            'created_at' => $this->dateTime()->defaultValue(null)->comment('Created at'),
            'updated_at' => $this->dateTime()->defaultValue(null)->comment('Updated at'),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Deleted at'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created at'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Deleted by'),
        ]);

        $this->addForeignKey('fk_agid_person_agid_organizational_unit', 'agid_person_organizational_unit_mm', 'agid_organizational_unit_id', 'agid_organizational_unit', 'id');
        $this->addForeignKey('fk_agid_person_agid_organizational_unit_p', 'agid_person_organizational_unit_mm', 'agid_person_id', 'agid_person', 'id');
    }


    public function down()
    {

       // Drop Table agid_person_organizational_unit_mm
       $this->dropTable('agid_person_organizational_unit_mm');
    }
}
