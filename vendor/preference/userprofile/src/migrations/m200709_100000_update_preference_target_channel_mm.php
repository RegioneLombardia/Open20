<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\db\Migration;
use yii\rbac\Permission;

/**
 * Class m200709_100000_update_preference_target_channel_mm */
class m200709_100000_update_preference_target_channel_mm extends Migration
{

    public function safeUp()
    {
        $this->renameTable('preference_target_channel_mm', 'preference_topic_channel_mm');
        $this->renameColumn('preference_topic_channel_mm','target_code','topic_code');
    }
    
    public function safeDown()
    {
        $this->renameTable('preference_topic_channel_mm','preference_target_channel_mm');
        $this->renameColumn('preference_target_channel_mm','topic_code','target_code');
    }

}
