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


class m200831_121400_add_data_newsletter_column_to_preference_campain_channel_mm extends Migration
{
    public function up()
    {
        $this->addColumn('preference_campain_channel_mm', 'date_newsletter', 
            $this->string(255)->null()->defaultValue(null)->comment('Data News Letter'));
    }

    public function down()
    {
        $this->dropColumn('preference_campain_channel_mm', 'date_newsletter');
    }
}



