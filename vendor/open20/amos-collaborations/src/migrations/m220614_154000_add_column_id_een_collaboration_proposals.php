<?php

use yii\db\Migration;

class m220614_154000_add_column_id_een_collaboration_proposals extends Migration
{
    const TABLE = '{{%collaboration_proposals}}';



    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'id_een', $this->string(100)->notNull()->after('end_date')->comment('ID EEN'));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'id_een');

        return true;
    }
}
