<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200203_181730_add_columns_event_1
 */
class m200626_105430_add_columns_calendar extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event_calendars', 'record_id', $this->integer()->comment('Record')->after('slot_duration'));
        $this->addColumn('event_calendars', 'classname', $this->string()->comment('Classname')->after('slot_duration'));
        $this->addColumn('event_calendars', 'max_participant', $this->integer()->comment('Max Participant')->after('slot_duration'));
        $this->addColumn('event_calendars', 'break_time', $this->integer()->comment('Break time (minute)')->after('slot_duration'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event_calendars', 'record_id');
        $this->dropColumn('event_calendars', 'classname');
        $this->dropColumn('event_calendars', 'break_time');
        $this->dropColumn('event_calendars', 'max_participant');

    }
}