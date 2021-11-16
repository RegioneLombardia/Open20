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


class m200930_160000_add_column_to_channel_mm extends Migration
{

    public function up()
    {
        $this->addColumn('preference_campain_channel_mm', 'mailup_error_message', $this->string(255)->null()->defaultValue(null)->after('mailup_status'));
    }

    public function down()
    {
        $this->dropColumn('preference_mailup_template', 'mailup_error_message');
    }

}