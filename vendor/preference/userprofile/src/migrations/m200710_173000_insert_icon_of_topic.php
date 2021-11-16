<?php
use yii\db\Migration;

/**
 * Class m200710_173000_insert_icon_of_topic 
 */
class m200710_173000_insert_icon_of_topic extends Migration
{

    public function safeUp()
    {

        $this->execute('SET SQL_SAFE_UPDATES = 0;');

        $this->execute("
        UPDATE `tag`
            SET `icon`='innovazione.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '1');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='ambiente-e-sviluppo-sostenibile.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '2');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='cultura-tempo-libero-e-turismo.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '3');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='mobilita-e-trasporti.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '4');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='informazioni-istituzionali.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '5');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='istruzione-formazione-e-lavoro.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '6');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='bandi-contributi-e-concorsi.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '7');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='allerta-e-info-di-pubblica-utilita.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '8');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='salute-e-prevenzione.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '9');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='sport.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '10');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='agricoltura-e-alimentazione.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '11');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='sociale.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '12');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='commercio.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '13');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='agricoltura-e-alimentazione.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '14');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='sicurezza.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '15');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='territorio.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '16');
       ");

        $this->execute("
        UPDATE `tag`
            SET `icon`='salute-e-prevenzione.svg'
            WHERE (`codice` LIKE 'pctopic_%') AND (SUBSTRING_INDEX(codice,'_',-1) LIKE '17');
       ");

        $this->execute('SET SQL_SAFE_UPDATES = 1;');

        return true;
    }

    public function safeDown()
    {
        $this->execute("
        UPDATE `tag`
            SET `icon` = NULL
            WHERE (`codice` LIKE 'pctopic_%');
       ");

        return true;
    }

}
