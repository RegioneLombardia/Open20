<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210712_184247_add_fields_sondaggi_domande_parentid
 */
class m210712_184247_add_fields_sondaggi_domande_parentid extends Migration
{
    const TABLE = '{{%sondaggi_domande}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'parent_id', $this->integer()->after('sondaggi_map_id'));
        $this->addColumn(self::TABLE, 'is_parent', $this->integer()->after('parent_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'parent_id');
        $this->dropColumn(self::TABLE, 'is_parent');
    }
}
