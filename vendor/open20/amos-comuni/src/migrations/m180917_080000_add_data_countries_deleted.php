<?php
use open20\amos\core\migration\AmosMigrationPermissions;


/**
 * Class m180917_080000_add_data_countries_deleted*/
class m180917_080000_add_data_countries_deleted extends AmosMigrationPermissions
{
    public function safeUp() {

        $this->batchInsert(
            "istat_nazioni",
            [ 'id',  'nome',  'nome_inglese',  'codice_catastale',  'istat_continenti_id', 'data_soppressione', 'soppressa'],
            [
                [ '2000061',  'Hong Kong',  'Hong Kong',  'Z221',  3, '1997-07-01', 1],
            ]
        );

        return true;

    }

    public function safeDown() {

        /**
         * This will remove all data and columns inserted above
         */

            $this->delete(
                "istat_nazioni",
                [ "id" => 2000061 ]
            );

        return true;
    }

}