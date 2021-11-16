<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200306_075522_insert_rules
 */
class m200330_175545_insert_rules2 extends Migration
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
            'nome' => 'Telefono con prefix internazionale',
            'descrizione' => 'Verifica che sia stato inserito un numero telefonico con prefisso internazionale',
            'standard' => null,
            'custom' => 1,
            'namespace' => null,
            'codice_custom' => '\open20\amos\core\validators\PhoneValidator::className(), \'international\' => true,',
        ]);

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Telefono semplice',
            'descrizione' => 'Verifica che sia stato inserito un numero telefonico',
            'standard' => null,
            'custom' => 1,
            'namespace' => 'open20\amos\core\validators\PhoneValidator',
            'codice_custom' => null,
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