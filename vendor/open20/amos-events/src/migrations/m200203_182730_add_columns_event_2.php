<?php

use open20\amos\events\models\Event;
use yii\db\Migration;

/**
 * Class m200203_182730_add_columns_event_2
 */
class m200203_182730_add_columns_event_2 extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'email_invitation_custom',
            $this->string()->comment('View email di invito custom')->after('email_subscribe_view'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'email_invitation_custom');
    }
}