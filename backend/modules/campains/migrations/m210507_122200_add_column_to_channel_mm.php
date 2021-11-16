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


class m210507_122200_add_column_to_channel_mm extends Migration
{

    public function up()
    {
        $this->addColumn('preference_campain_channel_mm', 'mailup_errors', $this->integer()->defaultValue(0)->after('mailup_error_message')->comment('Numbur of exceptions'));
    }

    public function down()
    {
        $this->dropColumn('preference_mailup_template', 'mailup_errors');
    }

}