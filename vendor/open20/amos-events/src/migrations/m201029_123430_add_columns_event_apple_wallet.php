<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200714_175430_add_columns_event
 */
class m201029_123430_add_columns_event_apple_wallet extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event_invitation', 'apple_wallet_device_id', $this->string()->defaultValue(null)->after('googlepay_ticket_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event_invitation', 'apple_wallet_device_id');
    }
}