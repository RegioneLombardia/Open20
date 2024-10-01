@@ -0,0 +1,41 @@
<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210907_151604_insert_rules3
 */
class m210907_151604_insert_rules3 extends Migration
{
    const TABLE_TIPO    = '{{%sondaggi_domande_rule}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->insert(self::TABLE_TIPO,
            [
            'nome' => 'Numero',
            'descrizione' => 'Verifica che sia stato inserito un numero',
            'standard' => 'number',
            'custom' => 0,
            'namespace' => null,
            'codice_custom' => null,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(self::TABLE_TIPO,
            [
            'nome' => 'Numero'
        ]);
    }
}
