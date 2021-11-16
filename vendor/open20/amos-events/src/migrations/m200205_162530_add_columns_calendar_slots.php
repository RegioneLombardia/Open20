<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200204_182830_add_columns_event_3
 */
class m200205_162530_add_columns_calendar_slots extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event_calendars_slots', 'cellphone', $this->string()->after('user_id'));
        $this->addColumn('event_calendars_slots', 'affiliation', $this->string()->after('user_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event_calendars_slots', 'cellphone');
        $this->dropColumn('event_calendars_slots', 'affiliation');
    }
}