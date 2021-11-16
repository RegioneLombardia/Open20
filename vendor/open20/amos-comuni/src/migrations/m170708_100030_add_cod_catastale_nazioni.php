<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */
class m170708_100030_add_cod_catastale_nazioni extends \yii\db\Migration {

    public function safeUp() {
        $this->addColumn('istat_nazioni', 'codice_catastale', $this->string(255)->after('unione_europea'));
    }

    public function safeDown() {
        $this->dropColumn('istat_nazioni', 'codice_catastale');
    }

}
