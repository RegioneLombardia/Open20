<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;


class m200908_153000_add_mailup_columns_to_preference_campain_channel_mm extends Migration
{
    public function up()
    {
        //'mailup_sending_id', 'mailup_message_id', 'mailup_import_id', 'mailup_group_id', 'mailup_list_id'
        $this->addColumn('preference_campain_channel_mm', 'mailup_sending_id', $this->integer()->null()->defaultValue(null)->comment('Mailup parameter'));
        $this->addColumn('preference_campain_channel_mm', 'mailup_message_id', $this->integer()->null()->defaultValue(null)->comment('Mailup parameter'));
        $this->addColumn('preference_campain_channel_mm', 'mailup_import_id', $this->integer()->null()->defaultValue(null)->comment('Mailup parameter'));
        $this->addColumn('preference_campain_channel_mm', 'mailup_group_id', $this->integer()->null()->defaultValue(null)->comment('Mailup parameter'));
        $this->addColumn('preference_campain_channel_mm', 'mailup_list_id', $this->integer()->null()->defaultValue(null)->comment('Mailup parameter'));
    }

    public function down()
    {
        $this->dropColumn('preference_campain_channel_mm', 'mailup_sending_id');
        $this->dropColumn('preference_campain_channel_mm', 'mailup_message_id');
        $this->dropColumn('preference_campain_channel_mm', 'mailup_import_id');
        $this->dropColumn('preference_campain_channel_mm', 'mailup_group_id');
        $this->dropColumn('preference_campain_channel_mm', 'mailup_list_id');
    }
}



