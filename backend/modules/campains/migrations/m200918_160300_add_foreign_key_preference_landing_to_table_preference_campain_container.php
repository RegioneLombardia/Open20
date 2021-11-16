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


class m200918_160300_add_foreign_key_preference_landing_to_table_preference_campain_container extends Migration
{

    public function up()
    {
        // addColumn
        $this->addColumn('preference_campain_container', 'preference_landing_id', $this->integer()->defaultValue(null));
        
        // addForeignKey
        $this->addForeignKey(
            'fk-preference-landing-id',
            'preference_campain_container',
            'preference_landing_id',
            'preference_landing',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        // dropForeignKey
        $this->dropForeignKey ( 'fk-preference-landing-id', 'preference_campain_container' );
        // dropColumn
        $this->dropColumn('preference_campain_container', 'preference_landing_id');
    }

}