<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200501_075543_add_fields_sondaggi
 */
class m200501_075543_add_fields_sondaggi extends Migration
{
    const TABLE          = '{{%sondaggi}}';
    const TABLE_DOMANDE  = '{{%sondaggi_domande}}';
    const TABLE_RISPOSTE = '{{%sondaggi_risposte}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_DOMANDE, 'introduzione_condizionata',
            $this->text()->defaultValue(null)->after('introduzione'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_DOMANDE, 'introduzione_condizionata');
    }
}