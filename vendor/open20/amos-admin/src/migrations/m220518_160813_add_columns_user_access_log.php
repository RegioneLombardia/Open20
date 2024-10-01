<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * 
 */
class m220518_160813_add_columns_user_access_log extends Migration
{
    const TABLE = "{{%user_access_log}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'be_or_fe', $this->string()->after('impersonator_user_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'be_or_fe');
    }
}