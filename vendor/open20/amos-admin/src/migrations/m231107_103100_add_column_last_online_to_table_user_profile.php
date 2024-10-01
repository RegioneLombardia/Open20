<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m231107_103100_add_column_last_online_to_table_user_profile
 */
class m231107_103100_add_column_last_online_to_table_user_profile extends Migration
{
    const TABLE = "{{%user_profile}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            self::TABLE,
            'last_online',
            $this->dateTime()->null()->after('ultimo_logout')->comment('Last time online')
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'last_online');
    }
}