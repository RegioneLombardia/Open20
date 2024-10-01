<?php

use yii\db\Migration;

class m220805_154100_alter_column_title_max_length extends Migration
{
    const TABLE = '{{%collaboration_proposals}}';


    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'title', $this->string(256)->notNull()->comment('Title'));

        return true;
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE, 'title', $this->string()->notNull()->comment('Title'));

        return true;
    }
}
