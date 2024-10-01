<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 *
 * 
 * 
 */
class m211110_115500_alter_column_agid_organizational_unit_table extends Migration {


    /**
     * update table agid_organizational_unit    
     *
     * @return void
     */
    public function safeUp() {

        $this->alterColumn( "agid_organizational_unit", 
                            "id_organizational_unit", 
                            $this->string()->defaultValue(null)->unique()
                        );
    }

    /**
     * rollback update change
     *
     * @return void
     */
    public function safeDown() {
        return true;
    }

}