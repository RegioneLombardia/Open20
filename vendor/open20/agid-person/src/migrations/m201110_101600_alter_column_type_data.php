<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 *
 * 
 * 
 */
class m201110_101600_alter_column_type_data extends Migration {


    /**
     * update table agid_person
     *
     * @return void
     */
    public function safeUp() {

        $this->alterColumn( "agid_person", "date_end_assignment", "date" );
        $this->alterColumn( "agid_person", "date_start_settlement", "date" );
    }

    /**
     * rollback update change
     *
     * @return void
     */
    public function safeDown() {}

}