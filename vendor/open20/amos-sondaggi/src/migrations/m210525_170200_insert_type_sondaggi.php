<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210525_170200_insert_type_sondaggi
 */
class m210525_170200_insert_type_sondaggi extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande_tipologie}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $date = date('Y-m-d H:i:s');
        $this->insert(self::TABLE_DOMANDE,
            [
            'id' => 15,
            'tipologia' => 'Array (risposte multiple)',
            'descrizione' => 'Array (risposte multiple)',
            'attivo' => 1,
            'html_type' => 'array',
            'created_at' => $date,
            'updated_at' => $date,
            'deleted_at' => null,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
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