<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200529_183113_add_column_event_group_referent_mm extends Migration
{
    const TABLE_EVENT_LIST = "event_group_referent_mm";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_LIST,'status', $this->string()->after('id'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LIST,'status');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
