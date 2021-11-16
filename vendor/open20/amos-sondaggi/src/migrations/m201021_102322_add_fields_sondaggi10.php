<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m201021_102322_add_fields_sondaggi10
 */
class m201021_102322_add_fields_sondaggi10 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'visualizza_solo_titolo', $this->integer()->defaultValue(0)->after('status'));
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'visualizza_solo_titolo');
    }
}