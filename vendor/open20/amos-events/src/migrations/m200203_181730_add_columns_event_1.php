<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200203_181730_add_columns_event_1
 */
class m200203_181730_add_columns_event_1 extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'email_credential_subject',
            $this->integer()->comment('Soggetto credenziali')->after('email_subscribe_view'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'email_credential_subject');
    }
}