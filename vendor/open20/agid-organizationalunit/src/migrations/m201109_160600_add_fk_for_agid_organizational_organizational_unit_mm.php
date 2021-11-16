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


class m201109_160600_add_fk_for_agid_organizational_organizational_unit_mm extends Migration
{
    public function up()
    {
        // addForeignKey
        $this->addForeignKey(
            
            'fk-agid-organizational-unit-id',
            'agid_organizational_organizational_unit_mm',
            'agid_organizational_unit_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );

        // addForeignKey
        $this->addForeignKey(
            
            'fk-agid-organizational-unit-father-id',
            'agid_organizational_organizational_unit_mm',
            'agid_organizational_unit_father_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ('fk-agid-organizational-unit-id', 'agid_organizational_organizational_unit_mm');
        $this->dropForeignKey ('fk-agid-organizational-unit-father-id', 'agid_organizational_organizational_unit_mm');
    }

}