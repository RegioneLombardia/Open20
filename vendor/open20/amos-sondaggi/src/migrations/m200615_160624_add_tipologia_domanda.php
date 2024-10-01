<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200615_160624_add_tipologia_domanda
 */
class m200615_160624_add_tipologia_domanda extends Migration
{
    const TABLE = '{{%sondaggi_domande_tipologie}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('sondaggi_domande_tipologie', ['tipologia' => 'Modello', 'descrizione' => "Sarà visualizzato un menu a tendina contenente i modelli disponibili, di cui si potrà effettuare una selezione singola", 'attivo' => 1, 'html_type' =>'select']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('sondaggi_domande_tipologie', ['tipologia' => 'Modello', 'descrizione' => "Sarà visualizzato un menu a tendina contenente i modelli disponibili, di cui si potrà effettuare una selezione singola", 'attivo' => 1, 'html_type' =>'select']);
    }

}
