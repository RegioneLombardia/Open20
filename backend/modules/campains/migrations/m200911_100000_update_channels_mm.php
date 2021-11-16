<?php
use yii\db\Migration;

/**
 * Class m200911_100000_update_channels_mm */
class m200911_100000_update_channels_mm extends Migration
{
    public function safeUp()
    {
        $this->execute("
        ALTER TABLE preference_campain_channel_mm 
            CHANGE COLUMN start_date start_date DATETIME NULL DEFAULT NULL COMMENT 'Start date' ,
            CHANGE COLUMN end_date end_date DATETIME NULL DEFAULT NULL COMMENT 'End date' ;

        ALTER TABLE preference_campain_channel_mm 
            ADD COLUMN mailup_n_sent INT(11) NULL DEFAULT NULL COMMENT 'Mailup parameter' AFTER mailup_list_id;
            

        ");
        
    }
    
    public function safeDown()
    {
        $this->execute("
        ALTER TABLE preference_campain_channel_mm 
            CHANGE COLUMN start_date start_date DATE NULL DEFAULT NULL COMMENT 'Start date' ,
            CHANGE COLUMN end_date end_date DATE NULL DEFAULT NULL COMMENT 'End date' ;

        ALTER TABLE preference_campain_channel_mm DROP COLUMN mailup_n_sent;
        ");
    }

}
