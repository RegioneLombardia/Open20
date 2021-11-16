<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200301_155543_add_fields_sondaggi_dom
 */
class m200301_155543_add_fields_sondaggi_dom extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_RISPOSTE     = '{{%sondaggi_risposte}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_DOMANDE, 'abilita_ordinamento_risposte',
            $this->integer()->defaultValue(0)->after('sondaggi_domande_tipologie_id'));
        $this->addColumn(self::TABLE_RISPOSTE, 'ordinamento',
            $this->integer()->defaultValue(null)->after('sondaggi_risposte_predefinite_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE_DOMANDE, 'abilita_ordinamento_risposte');
        $this->dropColumn(self::TABLE_RISPOSTE, 'ordinamento');
    }
}