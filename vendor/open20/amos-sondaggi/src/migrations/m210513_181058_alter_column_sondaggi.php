<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210513_181058_alter_column_sondaggi
 */
class m210513_181058_alter_column_sondaggi extends Migration
{
    const TABLE = '{{%sondaggi}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'publish_date', 'date');
        $this->alterColumn(self::TABLE, 'close_date', 'date');
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return true;
    }
}