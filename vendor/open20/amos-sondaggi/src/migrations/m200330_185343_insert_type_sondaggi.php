<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200330_185343_insert_type_sondaggi
 */
class m200330_185343_insert_type_sondaggi extends Migration
{
    const TABLE          = '{{%sondaggi}}';
    const TABLE_DOMANDE  = '{{%sondaggi_domande_tipologie}}';
    const TABLE_RISPOSTE = '{{%sondaggi_risposte}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $date = date('Y-m-d H:i:s');
        $this->insert(self::TABLE_DOMANDE,
            [
            'id' => 13,
            'tipologia' => 'Data (con DatePicker)',
            'descrizione' => 'Data (con DatePicker)',
            'attivo' => 1,
            'html_type' => 'date',
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