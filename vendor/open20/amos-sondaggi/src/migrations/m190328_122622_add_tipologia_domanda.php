<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190227_143522_create_sondaggi_model_content
 */
class m190328_122622_add_tipologia_domanda extends Migration
{
    const TABLE = '{{%sondaggi_content_model}}';
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('sondaggi_domande_tipologie', ['tipologia' => 'Allegato (singolo)', 'descrizione' => "Si potrà caricare un allegato", 'attivo' => 1, 'html_type' => 'file']);
        $this->insert('sondaggi_domande_tipologie', ['tipologia' => 'Allegati (multiplo)', 'descrizione' => "Si potranno caricare degli allegati", 'attivo' => 1, 'html_type' => 'file-multiple']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->delete('sondaggi_domande_tipologie', ['tipologia' => 'Allegato (singolo)', 'descrizione' => "Si potrà caricare un allegato", 'attivo' => 1, 'html_type' => 'file']);
        $this->delete('sondaggi_domande_tipologie', ['tipologia' => 'Allegati (multiplo)', 'descrizione' => "Si potranno caricare degli allegati", 'attivo' => 1, 'html_type' => 'file-multiple']);

    }

}
