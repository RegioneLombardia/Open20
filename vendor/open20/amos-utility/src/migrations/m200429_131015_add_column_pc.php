<?php

use yii\db\Migration;

class m200429_131015_add_column_pc extends Migration
{
    const TABLE_NAME = '{{%bullet_counters}}';

    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->addColumn(self::TABLE_NAME, 'pre_counter', $this->integer()->defaultValue(0)->after('counter'));
    }

    public function down()
    {
        $this->dropColumn(self::TABLE_NAME, 'pre_counter');
    }
}