<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210713_233431_add_fields_sondaggi_domande_columns
 */
class m210713_233431_add_fields_sondaggi_domande_columns extends Migration
{
    const TABLE = '{{%sondaggi_domande}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'multi_columns', $this->text()->null()->after('parent_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'multi_columns');
    }
}
