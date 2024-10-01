<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190925_072322_add_fields_sondaggi6
 */
class m190925_072322_add_fields_sondaggi6 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'forza_lingua',
            $this->string()->defaultValue(null)->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {      
        $this->dropColumn(self::TABLE, 'forza_lingua');
    }
}