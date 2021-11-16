<?php
use yii\db\Migration;

/**
 * Class m200916_124800_tag_update
 */
class m200916_124800_tag_update extends Migration
{

    public function safeUp()
    {

        $this->addColumn('tag', 'pc_active', $this->boolean()->defaultValue(true)->null()->comment('Order of targets')->after('pc_topic_order'));
        
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('tag', 'pc_active');
      
        return true;
    }

}
