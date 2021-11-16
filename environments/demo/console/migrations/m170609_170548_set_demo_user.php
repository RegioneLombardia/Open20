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

class m170609_170548_set_demo_user extends Migration
{
    const USER_TABLE = "user";
    const USER_PROFILE_TABLE = "user_profile";

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->update(self::USER_TABLE, [
            'username' => 'demo',
            'auth_key' => '8uSwKw27mKv-SprmqWzK8K5gvlpGnr8v',
            'password_hash' => '$2y$13$DWkzsaBL0tvJFn0jLH3cKeX903BtkDtjqI8tF/HrdYts00btbRsqa'
        ], 'username = "admin"');
    }

    public function safeDown()
    {
        $this->update(self::USER_TABLE, [
            'username' => 'admin',
            'auth_key' => '1cd87f5976c0893cb50d0758f528963f',
            'password_hash' => '$2y$13$76oNWmUm7XRTXGUPSJNcI.UZ1Ni5Pg475ZiDcpr0/6T4FVwh9cRQe'
        ], 'username = "demo"');
    }
}
