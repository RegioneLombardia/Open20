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


class m200904_145200_add_column_order_to_preference_campain_section extends Migration
{

    public function up()
    {
        $this->addColumn('preference_campain_section', 'order', $this->integer()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('preference_campain_section', 'order');
    }   

}