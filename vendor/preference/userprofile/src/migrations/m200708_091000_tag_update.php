<?php
use yii\db\Migration;

/**
 * Class m200708_091000_tag_update 
 */
class m200708_091000_tag_update extends Migration
{

    public function safeUp()
    {

        $this->addColumn('tag', 'pc_target_order', $this->integer(11)->null()->comment('Order of targets')->after('codice'));
        $this->addColumn('tag', 'pc_topic_order', $this->integer(11)->null()->comment('Order of topic')->after('pc_target_order'));
        
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('tag', 'pc_target_order');
        $this->dropColumn('tag', 'pc_topic_order');
      
        return true;
    }

}
