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


class m201109_193000_add_fk_to_agid_organizational_unit extends Migration
{

    public function up()
    {
        // addColumn to agid_organization_unit
        $this->addColumn('agid_organizational_unit', 'user_profile_president_id', $this->integer()->null()->defaultValue(null));
        
        // addForeignKey
        $this->addForeignKey(
            'fk-user-profile-president-id',
            'agid_organizational_unit',
            'user_profile_president_id',
            'user_profile',
            'id',
            'SET NULL'
        );


        // addColumn to agid_organization_unit
        $this->addColumn('agid_organizational_unit', 'user_profile_vice_president_id', $this->integer()->null()->defaultValue(null));
        
        // addForeignKey
        $this->addForeignKey(
            'fk-user-profile-vice-president-id',
            'agid_organizational_unit',
            'user_profile_vice_president_id',
            'user_profile',
            'id',
            'SET NULL'
        );


    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ( 'fk-user-profile-president-id', 'agid_organizational_unit' );
        // dropColumn
        $this->dropColumn('agid_organizational_unit', 'user_profile_president_id');

        // dropForeignKey
        $this->dropForeignKey ( 'fk-user-profile-vice-president-id', 'agid_organizational_unit' );
        // dropColumn
        $this->dropColumn('agid_organizational_unit', 'user_profile_vice_president_id');
    }

}