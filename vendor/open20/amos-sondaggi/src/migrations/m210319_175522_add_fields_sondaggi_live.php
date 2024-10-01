<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m201222_102322_add_fields_sondaggi_type
 */
class m210319_175522_add_fields_sondaggi_live extends Migration
{
    const TABLE         = '{{%sondaggi}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'graphics_live', $this->integer()->after('sondaggio_type'));
        $this->addColumn(self::TABLE, 'how_show_live', $this->integer()->after('sondaggio_type'));
        $this->addColumn(self::TABLE, 'end_date_hour_live', $this->dateTime()->after('sondaggio_type'));
        $this->addColumn(self::TABLE, 'begin_date_hour_live', $this->dateTime()->after('sondaggio_type'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'graphics_live');
        $this->dropColumn(self::TABLE, 'how_show_live');
        $this->dropColumn(self::TABLE, 'end_date_hour_live');
        $this->dropColumn(self::TABLE, 'begin_date_hour_live');

    }
}