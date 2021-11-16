<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190524_173430_alter_table_event
 */
class m200124_181730_add_column_event_slots_management extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'slots_calendar_management', $this->integer(1)->defaultValue(0)->comment('Calendar manaagement')->after('has_tickets'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'slots_calendar_management');
    }

}
