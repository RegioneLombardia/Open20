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


class m201105_164500_add_fk_agid_organizational_unit_content_type_office extends Migration
{

    public function up()
    {
        // addColumn to AGID ORGANIZATIONAL with AGID CONTENT TYPE "office"
        $this->addColumn('documenti', 'agid_organizational_unit_content_type_office_id', $this->integer()->null()->defaultValue(null));
            //->comment('FK agid_organizational_unit with agid_content_type_id => Area');
        
        // addForeignKey
        $this->addForeignKey(
            'fk-agid-organizational-unit-content-type-office-id',
            'documenti',
            'agid_organizational_unit_content_type_office_id',
            'agid_organizational_unit',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ( 'fk-agid-organizational-unit-content-type-office-id', 'documenti' );
        // dropColumn
        $this->dropColumn('documenti', 'agid_organizational_unit_content_type_office_id');
    }

}