<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200306_075522_insert_rules
 */
class m200306_075522_insert_rules extends Migration
{
    const TABLE         = '{{%sondaggi_domande_rule_mm}}';
    const TABLE_TIPO    = '{{%sondaggi_domande_rule}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_TIPO, 'namespace', $this->text()->defaultValue(null)->after('descrizione'));
        $this->addColumn(self::TABLE_TIPO, 'standard', $this->text()->defaultValue(null)->after('namespace'));

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Email',
            'descrizione' => 'Verifica che sia stata inserita una mail corretta',
            'standard' => 'email',
            'custom' => 0,
            'namespace' => null,
            'codice_custom' => null,
        ]);

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Url',
            'descrizione' => 'Verifica che sia stato inserito un indirizzo web corretto (è necessario aggiungere anche il prefisso http o https).',
            'standard' => 'url',
            'custom' => 0,
            'namespace' => null,
            'codice_custom' => null,
        ]);

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Codice fiscale italiano (persona fisica)',
            'descrizione' => 'Verifica che sia stato inserito un codice fiscale italiano corretto per una persona fisica',
            'standard' => null,
            'custom' => 1,
            'namespace' => \open20\amos\core\validators\CFValidator::className(),
            'codice_custom' => null,
        ]);

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Codice fiscale/Partita iva (formati italiani)',
            'descrizione' => 'Verifica che sia stato inserito un codice fiscale o una partita iva corretta',
            'standard' => null,
            'custom' => 1,
            'namespace' => \open20\amos\core\validators\CfPivaValidator::className(),
            'codice_custom' => null,
        ]);

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Partita iva',
            'descrizione' => 'Verifica che sia stata inserita una partita iva corretta',
            'standard' => null,
            'custom' => 1,
            'namespace' => \open20\amos\core\validators\PIVAValidator::className(),
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