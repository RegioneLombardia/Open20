<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190924_092322_add_fields_sondaggi5
 */
class m190924_092322_add_fields_sondaggi5 extends Migration
{
    const TABLE         = '{{%sondaggi}}';
    const TABLE_DOMANDE = '{{%sondaggi_domande}}';
    const TABLE_MAP     = '{{%sondaggi_map}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_DOMANDE, 'domanda_condizionata_testo_libero',
            $this->integer()->defaultValue(null)->after('domanda_condizionata'));
        $this->addForeignKey(
            'fk-sondaggi_domande-domande_lib1', self::TABLE_DOMANDE, 'domanda_condizionata_testo_libero',
            self::TABLE_DOMANDE, 'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-sondaggi_domande-domande_lib1', self::TABLE_DOMANDE);
        $this->dropColumn(self::TABLE_DOMANDE, 'domanda_condizionata_testo_libero');
    }
}