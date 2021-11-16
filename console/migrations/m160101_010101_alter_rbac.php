<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use yii\db\Migration;

class m160101_010101_alter_rbac extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;

        ALTER TABLE auth_assignment MODIFY item_name VARCHAR(255);
        ALTER TABLE auth_assignment MODIFY user_id VARCHAR(255);

        ALTER TABLE auth_item MODIFY name VARCHAR(255);
        ALTER TABLE auth_item MODIFY rule_name VARCHAR(255);

        ALTER TABLE auth_item_child MODIFY parent VARCHAR(255);
        ALTER TABLE auth_item_child MODIFY child VARCHAR(255);

        ALTER TABLE auth_rule MODIFY name VARCHAR(255);

        SET FOREIGN_KEY_CHECKS = 1;
        ');
        
        return true;
    }

    public function safeDown() 
    {
        return true;
    }
}
