<?php

use yii\db\Migration;

class m220830_150500_insert_into_stage_of_development_table extends Migration
{
    const TABLE = '{{%collaboration_stage_of_development}}';

    public function safeUp()
    {
        $this->insert(self::TABLE, [
            'id' => 6,
            'name' => 'Non applicabile',
        ]);

        return true;
    }

    public function safeDown()
    {
        $this->delete(self::TABLE, ['name' => 'Non applicabile']);

        return true;
    }
}
