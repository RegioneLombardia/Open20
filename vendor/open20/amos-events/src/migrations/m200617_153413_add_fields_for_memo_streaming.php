<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200617_153413_add_fields_for_memo_streaming extends Migration
{
    const TABLE_EVENT_LANDING = "event_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_LANDING, 'event_communication_memo_id', $this->integer()->after('text_sending_memo'));
        $this->addColumn('event_communication', 'is_automatic', $this->integer(1)->defaultValue(0)->after('sent_at'));

        $this->addForeignKey('fk_event_landing_communication_memo_id',self::TABLE_EVENT_LANDING,'event_communication_memo_id', 'event_communication','id' );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropForeignKey('fk_event_landing_communication_memo_id',self::TABLE_EVENT_LANDING );
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'event_communication_memo_id');
        $this->dropColumn('event_communication', 'is_automatic');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
