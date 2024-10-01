<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200714_175430_add_columns_event
 */
class m201008_151530_add_columns_event_invitation_gpay_ticket_id extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event_invitation', 'googlepay_ticket_id', $this->string()->defaultValue(null)->after('code'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event_invitation', 'googlepay_ticket_id');
    }
}