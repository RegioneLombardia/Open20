<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m201012_171813_add_event_enter_time extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('event', 'enter_time', $this->time()->defaultValue(null)->after('begin_date_hour'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn('event', 'enter_time');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
