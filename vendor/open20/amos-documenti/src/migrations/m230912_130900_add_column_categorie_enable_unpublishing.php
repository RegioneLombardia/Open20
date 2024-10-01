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


class m230912_130900_add_column_categorie_enable_unpublishing extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti_categorie', 'enable_countdown_unpublishing', $this->integer()->defaultValue(0)->comment('flag per abilitazione auto spubblicazione')->after('enable_annual_archiving') );
        $this->addColumn( 'documenti_categorie', 'years_countdown_unpublishing', $this->integer()->defaultValue(0)->after('enable_countdown_unpublishing') );
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti_categorie', 'enable_countdown_unpublishing');
        $this->dropColumn('documenti_categorie', 'years_countdown_unpublishing');

    }
}