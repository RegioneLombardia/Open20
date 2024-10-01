<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * this migration remove status of 
 * 
 * Manifestazione di interesse - module partnershipprofiles
 * 
 * 
 */
class m210622_172300_alter_type_of_colums extends Migration {

    /**
     * alter column
     *
     * @return void
     */
    public function safeUp() {

        $this->alterColumn("event", "agid_description", "text" );
    }

    /**
     * rollback update change
     *
     * @return void
     */
    public function safeDown() {}

}