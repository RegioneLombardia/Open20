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


class m211015_122000_add_colums_agid_person_table extends Migration
{

    public function up()
    {
        // addColumn to agid_person_profile_type
        $this->addColumn('agid_person', 'agid_person_profile_type_id', $this->integer()->null()->defaultValue(null));

        // addForeignKey
        $this->addForeignKey(
            'fk-agid-person-profile-type-id',
            'agid_person',
            'agid_person_profile_type_id',
            'agid_person_profile_type',
            'id',
            'SET NULL'
        );

        $this->addColumn('agid_person', 'id_persona', $this->string()->null()->defaultValue(null)->comment("lettera P seguita dall'ID del record"));
        $this->addColumn('agid_person', 'priorita', $this->integer()->null()->defaultValue(null));
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ('fk-agid-person-profile-type-id', 'agid_person' );
        // dropColumn
        $this->dropColumn('agid_person', 'agid_person_profile_type_id');

        $this->dropColumn('agid_person', 'id_persona');
        $this->dropColumn('agid_person', 'priorita');
    }

}