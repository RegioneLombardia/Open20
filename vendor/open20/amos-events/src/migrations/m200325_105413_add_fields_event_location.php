<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200325_105413_add_fields_event_location extends Migration
{
    const TABLE_EVENT_LOCATION = "event_location";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
     $this->addColumn(self::TABLE_EVENT_LOCATION, 'hidden', $this->integer()->defaultValue(0)->after('description')->comment('Hidden'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LOCATION, 'hidden');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
