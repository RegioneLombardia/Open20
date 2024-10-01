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


class m230906_120900_add_column_documenti_anno extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'year', $this->string(255)->null()->defaultValue(null)->comment('Archiviazione per anno')->after('author') );
     
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'year');

    }
}