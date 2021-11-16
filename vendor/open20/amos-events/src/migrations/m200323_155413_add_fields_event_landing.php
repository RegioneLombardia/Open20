<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200323_155413_add_fields_event_landing extends Migration
{
    const TABLE_EVENT_LANDING = "event_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

     $this->addColumn(self::TABLE_EVENT_LANDING, 'thank_you_subscribe', $this->text()->after('schedule'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'thank_you_registered', $this->text()->after('schedule'));
     $this->addColumn(self::TABLE_EVENT_LANDING, 'thank_you_waiting_list', $this->text()->after('schedule'));


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn(self::TABLE_EVENT_LANDING, 'thank_you_subscribe');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'thank_you_registered');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'thank_you_waiting_list');


        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
