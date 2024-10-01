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


class m211015_222800_add_columns_agid_organizational_unit_table extends Migration
{

    public function up()
    {
        // addColumn to agid_organizational_unit
        $this->addColumn('agid_organizational_unit', 'fax', $this->string()->null()->defaultValue(null));

        $this->addColumn('agid_organizational_unit', 'telephone_internal_use', $this->string()->null()->defaultValue(null));
        $this->addColumn('agid_organizational_unit', 'email_internal_use', $this->string()->null()->defaultValue(null));
        $this->addColumn('agid_organizational_unit', 'notes_internal_use', $this->text()->null()->defaultValue(null));
    }

    public function down()
    {

        $this->dropColumn('agid_organizational_unit', 'fax');
        $this->dropColumn('agid_organizational_unit', 'telephone_internal_use');
        $this->dropColumn('agid_organizational_unit', 'email_internal_use');
        $this->dropColumn('agid_organizational_unit', 'notes_internal_use');
    }

}