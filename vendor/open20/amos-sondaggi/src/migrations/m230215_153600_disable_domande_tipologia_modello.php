<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200615_160624_add_tipologia_domanda
 */
class m230215_153600_disable_domande_tipologia_modello extends Migration
{
    const TABLE = '{{%sondaggi_domande_tipologie}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('sondaggi_domande_tipologie',
            ['attivo' => false],
            ['tipologia' => 'Modello']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->update('sondaggi_domande_tipologie',
            ['attivo' => true],
            ['tipologia' => 'Modello']
        );
    }

}
