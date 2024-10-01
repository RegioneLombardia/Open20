<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m191025_084530_add_column_custom
 */
class m191025_084530_add_column_custom extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'email_ticket_layout_custom',
            $this->text()->defaultValue(null)->comment('Email ticket layout')->after('email_subscribe_view'));
        $this->addColumn('event', 'email_ticket_sender',
            $this->text()->defaultValue(null)->comment('Email ticket sender')->after('email_ticket_layout_custom'));
        $this->addColumn('event', 'email_ticket_subject',
            $this->text()->defaultValue(null)->comment('Email ticket subject')->after('email_ticket_sender'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'email_ticket_subject');
        $this->dropColumn('event', 'email_ticket_sender');
        $this->dropColumn('event', 'email_ticket_layout_custom');
    }
}