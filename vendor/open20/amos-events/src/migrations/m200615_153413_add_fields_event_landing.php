<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200615_153413_add_fields_event_landing extends Migration
{
    const TABLE_EVENT_LANDING = "event_landing";


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE_EVENT_LANDING, 'text_sending_memo', $this->text()->after('social_reg'));
        $this->addColumn(self::TABLE_EVENT_LANDING, 'subject_sending_memo', $this->text()->after('social_reg'));
        $this->addColumn(self::TABLE_EVENT_LANDING, 'date_sending_memo', $this->dateTime()->after('social_reg'));
        $this->addColumn(self::TABLE_EVENT_LANDING, 'date_begin_streaming', $this->dateTime()->after('social_reg'));
        $this->addColumn(self::TABLE_EVENT_LANDING, 'streaming_url', $this->string()->after('social_reg'));
        $this->addColumn(self::TABLE_EVENT_LANDING, 'streaming_type', $this->integer()->after('social_reg'));
        $this->addColumn(self::TABLE_EVENT_LANDING, 'enable_streaming', $this->integer()->defaultValue(0)->after('social_reg'));


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'text_sending_memo');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'subject_sending_memo');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'date_sending_memo');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'date_begin_streaming');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'streaming_url');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'streaming_type');
        $this->dropColumn(self::TABLE_EVENT_LANDING, 'enable_streaming');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
