<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210730_110600_preference_landing_documenti_add_field`.
 */
class m210730_110600_preference_landing_documenti_add_field extends Migration
{
    const TABLE = "preference_landing_documenti";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'sort_value', $this->integer()->null()->defaultValue(null)->after('documenti_id'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'sort_value');
    }
}
