<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210715_024650_add_fields_sondaggi_risposte_columns
 */
class m210715_024650_add_fields_sondaggi_risposte_columns extends Migration
{
    const TABLE = '{{%sondaggi_risposte}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'column', $this->integer()->null()->after('sondaggi_domande_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'columm');
    }
}
