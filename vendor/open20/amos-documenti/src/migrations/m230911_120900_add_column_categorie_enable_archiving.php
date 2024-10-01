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


class m230911_120900_add_column_categorie_enable_archiving extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti_categorie', 'enable_annual_archiving', $this->integer()->defaultValue(0)->comment('flag per abilitazione archiviazione annuale sul documento')->after('filemanager_mediafile_id') );
     
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti_categorie', 'enable_annual_archiving');

    }
}