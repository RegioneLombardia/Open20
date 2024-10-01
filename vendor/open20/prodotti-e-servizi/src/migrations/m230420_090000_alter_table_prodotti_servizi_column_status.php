<?php

use yii\db\Migration;

class m230420_090000_alter_table_prodotti_servizi_column_status extends Migration
{
    const TABLE_NAME = '{{%prodotti_servizi}}';

    public function safeUp()
    {
        $this->alterColumn(self::TABLE_NAME, 'status', $this->integer()->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE_NAME, 'status', $this->string()->notNull());
    }
}