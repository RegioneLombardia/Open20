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


class m201126_120900_add_column_documenti extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'author', $this->text()->null()->defaultValue(null)->comment('Autore') );
     
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'author');

    }
}