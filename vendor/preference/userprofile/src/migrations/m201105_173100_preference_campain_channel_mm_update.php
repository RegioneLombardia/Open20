<?php
use yii\db\Migration;

/**
 * Class m201105_173100_preference_campain_channel_mm_update
 */
class m201105_173100_preference_campain_channel_mm_update extends Migration
{

    public function safeUp()
    {

        $this->addColumn('preference_campain_channel_mm', 'date_app', $this->dateTime()->null()->comment('Date')->after('date_newsletter'));
        
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('preference_campain_channel_mm', 'date_app');
      
        return true;
    }

}
