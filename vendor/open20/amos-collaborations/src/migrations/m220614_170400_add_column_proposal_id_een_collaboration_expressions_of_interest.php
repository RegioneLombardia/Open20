<?php

use yii\db\Migration;

class m220614_170400_add_column_proposal_id_een_collaboration_expressions_of_interest extends Migration
{
    const TABLE = '{{%collaboration_expressions_of_interest}}';



    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'proposal_id_een', $this->string(100)->notNull()->after('collaboration_proposal_id')->comment('Proposal ID EEN'));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'proposal_id_een');

        return true;
    }
}
