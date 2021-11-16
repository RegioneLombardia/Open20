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
class m200922_151500_alter_column_content_title_table_preference_campain_container extends Migration {


    /**
     * update table preference_channel
     *
     * @return void
     */
    public function safeUp() {

        $this->alterColumn( "preference_campain_container", "content_news_link", "text" );
    }

    /**
     * rollback update change
     *
     * @return void
     */
    public function safeDown() {}

}