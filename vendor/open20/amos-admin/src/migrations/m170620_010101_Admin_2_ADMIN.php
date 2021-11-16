<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

class m170620_010101_Admin_2_ADMIN extends Migration
{
    public function safeUp()
    {
        $this->execute('
        SET FOREIGN_KEY_CHECKS = 0;
        UPDATE `auth_item`       SET name=\'ADMIN\'      WHERE LOWER(name)     = \'admin\';
        UPDATE `auth_item_child` SET parent=\'ADMIN\'    WHERE LOWER(parent)   = \'admin\';            
        UPDATE `auth_assignment` SET item_name=\'ADMIN\' WHERE LOWER(item_name)= \'admin\';
        SET FOREIGN_KEY_CHECKS = 1;
        ');

        return true;
    }

    public function safeDown()
    {
        return true;
    }
}
