<?php

use yii\db\Migration;

class m220929_144000_alter_column_proposal_title_max_length extends Migration
{
    const TABLE = '{{%collaboration_expressions_of_interest}}';


    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'proposal_title', $this->string(256)->notNull()->comment('Proposal Title'));

        return true;
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE, 'proposal_title', $this->string()->notNull()->comment('Proposal Title'));

        return true;
    }
}
