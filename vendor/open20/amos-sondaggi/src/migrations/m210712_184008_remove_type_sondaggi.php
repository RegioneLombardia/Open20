<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210712_184008_remove_type_sondaggi
 */
class m210712_184008_remove_type_sondaggi extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande_tipologie}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete(self::TABLE_DOMANDE,
            [
            'id' => 15
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
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
}
