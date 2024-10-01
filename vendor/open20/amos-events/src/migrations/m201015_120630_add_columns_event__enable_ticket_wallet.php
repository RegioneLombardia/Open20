<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200714_175430_add_columns_event
 */
class m201015_120630_add_columns_event__enable_ticket_wallet extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'enable_ticket_wallet', $this->integer(1)->defaultValue(0)->after('has_qr_code'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'enable_ticket_wallet');
    }
}