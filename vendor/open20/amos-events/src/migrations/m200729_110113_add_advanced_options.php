<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200729_110113_add_advanced_options extends Migration
{
    const TABLE_EVENT = "event";
    const TABLE_LANDING = "event_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT, 'advanced_options_event', $this->integer(0)->defaultValue(0)->after('event_id'));
        $this->addColumn(self::TABLE_EVENT, 'advanced_options_community', $this->integer(0)->defaultValue(0)->after('event_id'));
        $this->addColumn(self::TABLE_LANDING, 'advanced_options_landing', $this->integer(0)->defaultValue(0)->after('event_id'));


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT, 'advanced_options_event');
        $this->dropColumn(self::TABLE_EVENT, 'advanced_options_community');
        $this->dropColumn(self::TABLE_LANDING, 'advanced_options_landing');


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
