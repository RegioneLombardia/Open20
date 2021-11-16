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


class m200827_170200_add_sms_text_column_to_preference_campain_channel_mm extends Migration
{
    public function up()
    {
        $this->addColumn('preference_campain_channel_mm', 'sms_text', 
            $this->string(255)->null()->defaultValue(null)->comment('Sms Text'));
    }

    public function down()
    {
        $this->dropColumn('preference_campain_channel_mm', 'sms_text');
    }
}



