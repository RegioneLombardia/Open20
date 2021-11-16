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


class m200920_170000_add_column_to_campain extends Migration
{

    public function up()
    {
        // addColumn
        $this->addColumn('preference_campain', 'search_params', $this->text()->null()->defaultValue(null)->after('status'));
       
    }

    public function down()
    {
        // dropColumn
        $this->dropColumn('preference_campain', 'search_params');
    }

}