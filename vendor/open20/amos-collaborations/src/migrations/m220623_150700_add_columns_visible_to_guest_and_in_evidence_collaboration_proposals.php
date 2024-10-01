<?php

use yii\db\Migration;

class m220623_150700_add_columns_visible_to_guest_and_in_evidence_collaboration_proposals extends Migration
{
    const TABLE = '{{%collaboration_proposals}}';



    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'visible_to_guest', $this->tinyInteger(1)->defaultValue(0)->after('id_een')->comment('Visible to Guest'));
        $this->addColumn(self::TABLE, 'in_evidence', $this->tinyInteger(1)->defaultValue(0)->after('visible_to_guest')->comment('In evidence'));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'visible_to_guest');
        $this->dropColumn(self::TABLE, 'in_evidence');

        return true;
    }
}
