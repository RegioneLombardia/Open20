<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200924_102322_add_fields_sondaggi9
 */
class m200924_102322_add_fields_sondaggi9 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'sondaggio_chiuso_frontend', $this->integer()->defaultValue(0)->after('status'));
        $this->addColumn(self::TABLE, 'thank_you_page_sondaggio_chiuso',
            $this->text()->after('sondaggio_chiuso_frontend'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'sondaggio_chiuso_frontend');
        $this->dropColumn(self::TABLE, 'thank_you_page_sondaggio_chiuso');      
    }
}