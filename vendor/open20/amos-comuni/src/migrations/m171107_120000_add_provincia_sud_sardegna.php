<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171031_160001_add_auth_item_importatore_comuni*/
class m171107_120000_add_provincia_sud_sardegna extends AmosMigrationPermissions
{
    public function safeUp() {
        $this->batchInsert(
            "istat_province",
            [ 'id',  'nome',  'sigla',  'capoluogo',  'soppressa',  'istat_citta_metropolitane_id',  'istat_regioni_id'],
            [[ '111',  'Sud Sardegna',  'SU',  '0',  '0',  NULL,  '20'] ]
        );
    }

    public function safeDown() {
        $this->delete(
            "istat_province",
            [ "id" => "111",  ]
        );
    }
}