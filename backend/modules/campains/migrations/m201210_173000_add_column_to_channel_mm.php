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


class m201210_173000_add_column_to_channel_mm extends Migration
{

    public function up()
    {
        $this->addColumn('preference_campain_channel_mm', 'mailup_stats_number_comunication_clicks', $this->integer()->null()->defaultValue(null)->after('mailup_stats_number_comunication_sent')->comment('Num di click dopo xx tempo'));
        $this->addColumn('preference_campain_channel_mm', 'mailup_stats_number_comunication_views', $this->integer()->null()->defaultValue(null)->after('mailup_stats_number_comunication_clicks')->comment('Num di visualizzazioni dopo xx tempo'));
    }

    public function down()
    {
        $this->dropColumn('preference_mailup_template', 'mailup_stats_number_comunication_clicks');
        $this->dropColumn('preference_mailup_template', 'mailup_stats_number_comunication_views');
    }

}