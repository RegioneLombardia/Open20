<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m161109_173720_init_default_cwh_view extends Migration
{


    public function safeUp()
    {
        $this->execute("
        CREATE OR REPLACE VIEW `cwh_nodi_view`  AS  
      
        select concat('user-',`user`.`id`) AS `id`,2 AS `cwh_config_id`,
        `user`.`id` AS `record_id`,'common\\\\models\\\\User' AS `classname`,
        `user`.`created_at` AS `created_at`,`user`.`updated_at` AS `updated_at`,`user`.`deleted_at` AS `deleted_at`,`user`.`created_by` AS `created_by`,`user`.`updated_by` AS `updated_by`,`user`.`deleted_by` AS `deleted_by` 
        from (`user` join `user_profile` on((`user`.`id` = `user_profile`.`user_id`))) 
        ;");

        return true;
    }

    public function safeDown()
    {

        return true;
    }
}
