<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200402_173332_insert_rules_majority
 */
class m200402_173332_insert_rules_majority extends Migration
{
    const TABLE         = '{{%sondaggi_domande_rule_mm}}';
    const TABLE_TIPO    = '{{%sondaggi_domande_rule}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Maggiore età',
            'descrizione' => 'Verifica che sia stata inserita una data di nascita di un maggiorenne (ad oggi)',
            'standard' => null,
            'custom' => 1,
            'namespace' => null,
            'codice_custom' => '\open20\amos\sondaggi\validators\DateDiff::className(), \'value\' => 18,',
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}