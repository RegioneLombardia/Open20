<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m191106_172322_add_fields_sondaggi7
 */
class m191106_172322_add_fields_sondaggi7 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'abilita_criteri_valutazione', $this->integer()->defaultValue(0)->after('status'));
        $this->addColumn(self::TABLE, 'n_max_valutatori',
            $this->integer()->defaultValue(0)->after('abilita_criteri_valutazione'));       
        $this->addColumn(self::TABLE_DOMANDE, 'punteggio_max',
            $this->integer()->defaultValue(0)->after('sondaggi_domande_tipologie_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'abilita_criteri_valutazione');
        $this->dropColumn(self::TABLE, 'n_max_valutatori');      
        $this->dropColumn(self::TABLE_DOMANDE, 'punteggio_max');
    }
}