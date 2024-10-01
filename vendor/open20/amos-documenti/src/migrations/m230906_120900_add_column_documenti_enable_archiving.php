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


class m230906_120900_add_column_documenti_enable_archiving extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'enable_annual_archiving', $this->integer()->defaultValue(0)->comment('flag per abilitazione archiviazione annuale sul documento')->after('year') );
     
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'enable_annual_archiving');

    }
}