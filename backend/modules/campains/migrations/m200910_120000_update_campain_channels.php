<?php
use yii\db\Migration;

/**
 * Class m200910_120000_update_campain_channels */
class m200910_120000_update_campain_channels extends Migration
{
    public function safeUp()
    {
        $this->execute("
        ALTER TABLE `preference_campain_channel_mm` 
            CHANGE COLUMN `template_id` `template_id` INT(11) NULL DEFAULT NULL COMMENT 'Template Id' AFTER `mailup_status`,
            CHANGE COLUMN `created_at` `created_at` DATETIME NULL DEFAULT NULL COMMENT 'Created at' AFTER `template_id`,
            CHANGE COLUMN `updated_at` `updated_at` DATETIME NULL DEFAULT NULL COMMENT 'Updated at' AFTER `created_at`,
            CHANGE COLUMN `deleted_at` `deleted_at` DATETIME NULL DEFAULT NULL COMMENT 'Deleted at' AFTER `updated_at`,
            CHANGE COLUMN `created_by` `created_by` INT(11) NULL DEFAULT NULL COMMENT 'Created by' AFTER `deleted_at`,
            CHANGE COLUMN `updated_by` `updated_by` INT(11) NULL DEFAULT NULL COMMENT 'Updated by' AFTER `created_by`,
            CHANGE COLUMN `deleted_by` `deleted_by` INT(11) NULL DEFAULT NULL COMMENT 'Deleted by' AFTER `updated_by`,
            CHANGE COLUMN `mailup_status` `mailup_status` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Mailup parameter' ;
        ");
        
    }
    
    public function safeDown()
    {
        $this->execute("
        ALTER TABLE `preference_campain_channel_mm` 
            CHANGE COLUMN `mailup_status` `mailup_status` INTEGER(11) NULL DEFAULT NULL COMMENT 'Mailup parameter' ;
        ");
    }

}
