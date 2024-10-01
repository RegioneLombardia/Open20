<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200714_175430_add_columns_event
 */
class m201007_145930_add_columns_event_gpay_ticket_id extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'googlepay_ticket_class_id', $this->string()->defaultValue(null)->after('ics_libero'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'googlepay_ticket_class_id');
    }
}