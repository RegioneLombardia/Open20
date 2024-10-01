<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigration;

class m230712_160404_alter_table_stati_civili extends AmosMigration
{
    public function safeUp()
    { 
        $this->update('user_profile_stati_civili', ['nome'=>'Vedovo/a'], ['nome'=>'Diploma di maturità']);
        
    }

    public function safeDown()
    {
        echo "m230712_160404_alter_table_stati_civili cannot be reverted.\n";
        return false;
    }
}