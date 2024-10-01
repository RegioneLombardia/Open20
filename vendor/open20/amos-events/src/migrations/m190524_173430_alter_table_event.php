<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190524_173430_alter_table_event
 */
class m190524_173430_alter_table_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Event::tableName(), 'ticket_layout_view', $this->string(400)->null()->comment('Event ticket layout view')->after('event_full_page_view'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Event::tableName(), 'ticket_layout_view');
    }

}
