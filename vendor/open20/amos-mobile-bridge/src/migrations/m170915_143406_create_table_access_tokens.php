<?php

use yii\db\Migration;

class m170915_143406_create_table_access_tokens extends Migration
{
    public function safeUp()
    {
        $this->createTable('access_tokens', [
            'access_token' => $this->string(32)->comment('Access Token'),
            'user_id' => $this->integer(11)->comment('User id'),
            'device_info' => $this->text()->null()->comment('Device info'),
            'ip' => $this->string(32)->null()->comment('IP info'),
            'location' => $this->string(255)->null()->comment('Location'),
            'fcm_token' => $this->string(255)->null()->comment('FCM Token'),
            'device_os' => $this->string(64)->null()->comment('Device OS'),
            'logout_at' => $this->dateTime()->null(),
            'logout_by' => $this->integer(11)->null(),
            'created_at' => $this->dateTime()->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_at' => $this->dateTime()->null(),
            'updated_by' => $this->integer(11)->null(),
            'deleted_at' => $this->dateTime()->null(),
            'deleted_by' => $this->integer(11)->null(),
        ]);

        $this->addColumn('user', 'access_token', \yii\db\Schema::TYPE_STRING);
    }

    public function safeDown()
    {
        $this->dropColumn('user', 'access_token');
        $this->dropTable('access_tokens');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170915_143406_create_table_access_tokens cannot be reverted.\n";

        return false;
    }
    */
}
