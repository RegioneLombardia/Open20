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


class m211015_194100_add_columns_agid_organizational_unit_table extends Migration
{

    public function up()
    {
        // addColumn to agid_organizational_unit
        $this->addColumn('agid_organizational_unit', 'agid_organizational_unit_profile_type_id', $this->integer()->null()->defaultValue(null));

        // addForeignKey
        $this->addForeignKey(
            'fk-agid-organizational-unit-profile_type-id',
            'agid_organizational_unit',
            'agid_organizational_unit_profile_type_id',
            'agid_organizational_unit_profile_type',
            'id',
            'SET NULL'
        );

        $this->addColumn('agid_organizational_unit', 'id_organizational_unit', $this->string()->null()->defaultValue(null)->comment("'ID Unità Organizzativa' campo a contenuto alfanumerico"));
        $this->addColumn('agid_organizational_unit', 'priorita', $this->integer()->null()->defaultValue(null));
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ('fk-agid-organizational-unit-profile_type-id', 'agid_organizational_unit' );
        // dropColumn
        $this->dropColumn('agid_organizational_unit', 'agid_organizational_unit_profile_type_id');

        $this->dropColumn('agid_organizational_unit', 'id_organizational_unit');
        $this->dropColumn('agid_organizational_unit', 'priorita');
    }

}