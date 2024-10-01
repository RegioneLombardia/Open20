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


class m220211_113700_add_columns_agid_person_table_1 extends Migration
{

    public function up()
    { 
        // addColumn to agid_person
        $this->addColumn('agid_person', 'cellphone_internal_use', $this->string()->null()->defaultValue(null));
    }

    public function down() 
    {
        $this->dropColumn('agid_person', 'cellphone_internal_use');
    }

}