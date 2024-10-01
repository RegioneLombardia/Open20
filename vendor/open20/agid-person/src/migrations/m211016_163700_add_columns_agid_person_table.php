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


class m211016_163700_add_columns_agid_person_table extends Migration
{

    public function up()
    {
        // addColumn to agid_person
        $this->addColumn('agid_person', 'telephone_internal_use', $this->string()->null()->defaultValue(null));
        $this->addColumn('agid_person', 'email_internal_use', $this->string()->null()->defaultValue(null));
        $this->addColumn('agid_person', 'notes_internal_use', $this->text()->null()->defaultValue(null));
        $this->addColumn('agid_person', 'service_status_internal_use', $this->text()->null()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('agid_person', 'telephone_internal_use');
        $this->dropColumn('agid_person', 'email_internal_use');
        $this->dropColumn('agid_person', 'notes_internal_use');
        $this->dropColumn('agid_person', 'service_status_internal_use');
    }

}