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
class m200922_135700_update_preference_channel_fields extends Migration {


    /**
     * update table preference_channel
     *
     * @return void
     */
    public function safeUp() {

        $this->update(
            // table
            'preference_channel', 
            // update columns with
            ['active' => "0"], 
            // where clause
            [ 
                'title' => "Telefonata",
            ]
        );
    }

    /**
     * rollback update change
     *
     * @return void
     */
    public function safeDown() {

        $this->update(
            // table
            'preference_channel', 
            // update columns with
            ['active' => "1"], 
            // where clause
            [ 
                'title' => "Telefonata",
            ]
        );
    }
}