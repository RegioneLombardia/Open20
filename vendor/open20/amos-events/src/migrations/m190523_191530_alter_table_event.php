<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190523_191530_alter_table_event
 */
class m190523_191530_alter_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Event::tableName(), 'event_closed_page_view', $this->string(400)->null()->comment('Event closed page view')->after('email_view'));
        $this->addColumn(Event::tableName(), 'event_full_page_view', $this->string(400)->null()->comment('Event full page view')->after('event_closed_page_view'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Event::tableName(), 'event_closed_page_view');
        $this->dropColumn(Event::tableName(), 'event_full_page_view');
    }

}
