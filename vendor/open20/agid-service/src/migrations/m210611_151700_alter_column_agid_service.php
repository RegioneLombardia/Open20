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
class m210611_151700_alter_column_agid_service extends Migration {


    /**
     * update table agid_service
     *
     * @return void
     */
    public function safeUp() {

        $this->alterColumn( "agid_service", "further_information", "text" );
    }

    /**
     * rollback agid_service
     *
     * @return void
     */
    public function safeDown() {}

}