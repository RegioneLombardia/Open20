<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m190524_173430_alter_table_event
 */
class m191008_154530_add_column_event extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'seats_management', $this->integer(1)->defaultValue(0)->comment('Seats management')->after('has_tickets'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'seats_management');
    }

}
