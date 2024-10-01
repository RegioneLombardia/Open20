<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m191106_182322_add_fields_sondaggi8
 */
class m191106_182322_add_fields_sondaggi8 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_DOMANDE, 'domanda_per_criteri',
            $this->integer()->defaultValue(0)->after('sondaggi_domande_tipologie_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'domanda_per_criteri');
    }
}