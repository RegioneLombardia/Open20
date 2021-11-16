<?php
use yii\db\Migration;

/**
 * Class m200915_100000_update_workflow */
class m200915_100000_update_workflow extends Migration
{
    public function safeUp()
    {
        $this->execute("
        INSERT INTO sw_metadata (workflow_id, status_id, `key`, value) VALUES ('PreferenceCampainChannelMmWorkflow', 'SENDINGINPROGRESS', 'buttonLabel', 'Invia');
        DELETE FROM sw_metadata WHERE (workflow_id = 'PreferenceCampainChannelMmWorkflow') and (status_id = 'SENDINGINPROGRESS') and ( `key` = 'description');
        DELETE FROM sw_metadata WHERE (workflow_id = 'PreferenceCampainWorkflow') and (status_id = 'APPROVED') and (`key` = 'description');   
        ");
        
    }
    
    public function safeDown()
    {
        return true;
    }

}
