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
class m201110_121100_alter_column_documenti extends Migration {


    /**
     * update table preference_channel
     *
     * @return void
     */
    public function safeUp() {

        $this->alterColumn( "documenti", "end_date", $this->date()->null()->defaultValue(null) );
        $this->alterColumn( "documenti", "start_date", $this->date()->null()->defaultValue(null) );
        $this->alterColumn( "documenti", "protocol_date", $this->date()->null()->defaultValue(null) );
    }

    /**
     * rollback update change
     *
     * @return void
     */
    public function safeDown() {}

}