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


class m210309_174000_add_new_colums_to_agid_organizational_unit extends Migration
{

    public function up()
    {
        // addColumn to agid_organization_unit
        $this->addColumn('agid_organizational_unit', 'website_url', $this->text()->null()->defaultValue(null)->comment('Url Sito Web'));

    }

    public function down()
    {

        // dropColumn
        $this->dropColumn('agid_organizational_unit', 'website_url');
    }

}