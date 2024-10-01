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


class m230908_120900_add_column_documenti_start_archiving extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'date_start_archiving', $this->date()->defaultValue(null)->comment('data di inizio archiviazione')->after('enable_annual_archiving') );
        $this->addColumn( 'documenti', 'stop_archiving', $this->integer()->defaultValue(null)->comment('flag per bloccare completamente archiviazione')->after('date_start_archiving') );
     
    }


    public function down()
    {

        // dropColumn to documenti
        $this->dropColumn('documenti', 'date_start_archiving');
        $this->dropColumn('documenti', 'stop_archiving');

    }
}