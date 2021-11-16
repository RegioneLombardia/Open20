<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200324_155413_add_fields_event_invitation_sent extends Migration
{
    const TABLE_EVENT_INTIVATION_SENT= "event_invitation_sent";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {

     $this->addColumn(self::TABLE_EVENT_INTIVATION_SENT, 'template', $this->string()->after('send_at'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn(self::TABLE_EVENT_INTIVATION_SENT, 'template');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
