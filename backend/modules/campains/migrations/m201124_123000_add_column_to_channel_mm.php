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


class m201124_123000_add_column_to_channel_mm extends Migration
{

    public function up()
    {
        $this->addColumn('preference_campain_channel_mm', 'mailup_stats_number_comunication_sent', $this->integer()->null()->defaultValue(null)->after('mailup_error_message')->comment('Num messaggi inviati da mailup'));
        $this->addColumn('preference_campain_channel_mm', 'mailup_stats_number_comunication_bounces', $this->integer()->null()->defaultValue(null)->after('mailup_error_message')->comment('Num messaggi rimbalzati'));
    }

    public function down()
    {
        $this->dropColumn('preference_mailup_template', 'mailup_stats_number_comunication_sent');
        $this->dropColumn('preference_mailup_template', 'mailup_stats_number_comunication_bounces');
    }

}