<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

/**
 * Class m180903_112010_rimozione_comuni_non_esistenti
 * Questa migration rimuove i comuni non esistenti (verifica effettuata anche manualmente)
 * nella lista estratta dal sito dell'agenzia delle entrate ma presenti nei file
 * istat importati.
 */
class m180903_112010_rimozione_comuni_non_esistenti extends \yii\db\Migration
{

    public function safeUp()
    {

        $this->delete('istat_comuni', ['id' => 27810]);
        $this->delete('istat_comuni', ['id' => 701806]);
        $this->delete('istat_comuni', ['id' => 702802]);

        return true;
    }

    public function safeDown()
    {
        echo "m180903_112010_rimozione_comuni_non_esistenti cannot be reverted";
        return true;
    }

}