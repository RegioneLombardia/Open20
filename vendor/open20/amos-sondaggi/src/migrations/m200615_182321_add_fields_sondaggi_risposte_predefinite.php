<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m200615_182321_add_fields_sondaggi_risposte_predefinite
 */
class m200615_182321_add_fields_sondaggi_risposte_predefinite extends Migration {

    const TABLE = '{{%sondaggi_risposte_predefinite}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn(self::TABLE, 'modello_id',
                $this->integer()->defaultValue(null)->after('sondaggi_domande_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn(self::TABLE, 'modello_id');
    }

}
