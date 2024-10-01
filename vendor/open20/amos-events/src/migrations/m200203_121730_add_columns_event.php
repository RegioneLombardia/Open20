<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200203_121730_add_columns_event
 */
class m200203_121730_add_columns_event extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'sent_credential',
            $this->integer()->comment('Sent credential')->after('ticket_layout_view'));
        $this->addColumn('event', 'email_credential_view',
            $this->string()->comment('Sent credential')->after('email_subscribe_view'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'sent_credential');
        $this->dropColumn('event', 'email_credential_view');
    }
} 