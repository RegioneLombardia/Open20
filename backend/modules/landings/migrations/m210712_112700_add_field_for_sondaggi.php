<?php

use yii\db\Migration;

/**
 * Handles the creation of table `m210712_112700_add_field_for_sondaggi`.
 */
class m210712_112700_add_field_for_sondaggi extends Migration
{
    const TABLE = "preference_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'sondaggi_id', $this->integer()->null()->defaultValue(null)->after('url'));
        $this->addForeignKey('fk_preference_landing_sondaggi_id',self::TABLE, 'sondaggi_id', 'sondaggi', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_preference_landing_sondaggi_id',self::TABLE);
        $this->dropColumn(self::TABLE, 'sondaggi_id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
