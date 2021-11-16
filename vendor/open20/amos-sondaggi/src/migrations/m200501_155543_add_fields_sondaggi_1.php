<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200501_155543_add_fields_sondaggi_1
 */
class m200501_155543_add_fields_sondaggi_1 extends Migration
{
    const TABLE          = '{{%sondaggi}}';
    const TABLE_DOMANDE  = '{{%sondaggi_domande_tipologie}}';
    const TABLE_RISPOSTE = '{{%sondaggi_risposte}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
                    INSERT INTO `sondaggi_domande_tipologie` (`id`, `tipologia`, `descrizione`, `attivo`, `html_type`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `version`) VALUES
                    (12,	'Testo in sola visualizzazione',	'Testo in sola visualizzazione',	1,	'descrizione',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}