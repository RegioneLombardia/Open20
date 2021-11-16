<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m201222_102322_add_fields_sondaggi_type
 */
class m201222_102322_add_fields_sondaggi_type extends Migration
{
    const TABLE         = '{{%sondaggi}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'sondaggio_type', $this->integer()->defaultValue(1)->after('visualizza_solo_titolo'));
        $this->addColumn(self::TABLE, 'sondaggio_live_community_id', $this->integer()->defaultValue(0)->after('sondaggio_type'));
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'sondaggio_live_community_id');
        $this->dropColumn(self::TABLE, 'sondaggio_type');
    }
}