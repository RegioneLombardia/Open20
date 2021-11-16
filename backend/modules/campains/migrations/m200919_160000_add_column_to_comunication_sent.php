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


class m200919_160000_add_column_to_comunication_sent extends Migration
{

    public function up()
    {
        // addColumn
        $this->addColumn('preference_communication_sent', 'phone', $this->string(255)->null()->defaultValue(null)->comment('Phone')->after('email'));
       
    }

    public function down()
    {
        // dropColumn
        $this->dropColumn('preference_communication_sent', 'phone');
    }

}