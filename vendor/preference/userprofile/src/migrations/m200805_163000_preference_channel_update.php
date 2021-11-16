<?php
use yii\db\Migration;

/**
 * Class m200805_163000_preference_channel_update 
 */
class m200805_163000_preference_channel_update extends Migration
{

    public function safeUp()
    {

        $this->addColumn('preference_channel', 'active', $this->boolean()->defaultValue(true)->null()->comment('Channel active')->after('title'));
        $this->update('preference_channel', ['title' => 'E-Mail'], ['id' => 1]);
        
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('preference_channel', 'active');
        $this->update('preference_channel', ['title' => 'Newsletter'], ['id' => 1]);
      
        return true;
    }

}
