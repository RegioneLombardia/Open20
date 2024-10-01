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


class m201112_021700_add_fk_agid_person_for_agid_organizational_unit extends Migration
{

    public function up()
    {
        // addColumn to agid_organization_unit
        $this->addColumn('agid_organizational_unit', 'agid_person_president_id', $this->integer()->null()->defaultValue(null));
        
        // addForeignKey
        $this->addForeignKey(
            'fk-agid-person-president-id',
            'agid_organizational_unit',
            'agid_person_president_id',
            'agid_person',
            'id',
            'SET NULL'
        );


        // addColumn to agid_organization_unit
        $this->addColumn('agid_organizational_unit', 'agid_person_vice_president_id', $this->integer()->null()->defaultValue(null));
        
        // addForeignKey
        $this->addForeignKey(
            'fk-agid-person-vice-president-id',
            'agid_organizational_unit',
            'agid_person_vice_president_id',
            'agid_person',
            'id',
            'SET NULL'
        );


    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ( 'fk-agid-person-president-id', 'agid_organizational_unit' );
        // dropColumn
        $this->dropColumn('agid_organizational_unit', 'agid_person_president_id');

        // dropForeignKey
        $this->dropForeignKey ( 'fk-agid-person-vice-president-id', 'agid_organizational_unit' );
        // dropColumn
        $this->dropColumn('agid_organizational_unit', 'agid_person_vice_president_id');
    }

}