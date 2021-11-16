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
 * Class m181113_130010_aggiunta_codice_catastale_serbia
 * Aggiunta codice catastale Serbia, Repubblica di
 */
class m181113_130010_aggiunta_codice_catastale_serbia extends \yii\db\Migration
{

    private function updateValues($arrayVariazioni) {

        foreach($arrayVariazioni as $istatCodiciCatastali) {
            /**
             * Aggiorno il codice catastale contenuto in $istatCodiciCatastali[1]
             * per ogni comune con codice istat (id) contenuto in $istatCodiciCatastali[0]
             */
            $this->update('istat_nazioni', ['codice_catastale' => $istatCodiciCatastali[1]], ['id' => $istatCodiciCatastali[0]]);
        }

    }

    public function safeUp()
    {
        $this->updateValues([
            ["271","Z158"],
        ]);
        return true;
    }

    public function safeDown()
    {
        echo "m181113_130010_aggiunta_codice_catastale_serbia cannot be reverted";
        return true;
    }

}