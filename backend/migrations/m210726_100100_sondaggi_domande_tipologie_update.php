<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m210726_100100_sondaggi_domande_tipologie_update
 */
class m210726_100100_sondaggi_domande_tipologie_update extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('sondaggi_domande_tipologie', ['attivo' => 0], ['id' => 14, 'tipologia' => 'Modello']);
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('sondaggi_domande_tipologie', ['attivo' => 1], ['id' => 14, 'tipologia' => 'Modello']);
    }
}
