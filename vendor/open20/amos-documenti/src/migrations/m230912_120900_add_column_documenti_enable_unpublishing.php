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


class m230912_120900_add_column_documenti_enable_unpublishing extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'enable_countdown_unpublishing', $this->integer()->defaultValue(0)->comment('flag per abilitazione auto spubblicazione')->after('stop_archiving') );
     
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'enable_countdown_unpublishing');

    }
}