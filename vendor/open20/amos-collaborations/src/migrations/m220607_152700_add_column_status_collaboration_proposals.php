<?php

use yii\db\Migration;

class m220607_152700_add_column_status_collaboration_proposals extends Migration
{
    const TABLE = '{{%collaboration_proposals}}';



    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'status', $this->string()->notNull()->after('title')->comment('Workflow Status'));

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'status');

        return true;
    }
}
