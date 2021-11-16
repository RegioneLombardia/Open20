<?php

use yii\db\Migration;

class m201110_143406_add_pk_to_access_tokens extends Migration
{
    public function safeUp()
    {

        $this->addColumn('access_tokens', 'id', $this->primaryKey()->after('access_token'));
    }

    public function safeDown()
    {

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
