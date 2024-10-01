<?php

use yii\db\Migration;

class m220621_122700_add_column_phone_expressions_of_interest extends Migration
{
    const TABLE = '{{%collaboration_expressions_of_interest}}';



    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'proposer_phone', $this->string()->notNull()->after('proposer_email')->comment('Proposer phone number'));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'proposer_phone');

        return true;
    }
}
