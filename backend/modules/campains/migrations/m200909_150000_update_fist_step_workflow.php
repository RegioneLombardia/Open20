<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\db\Migration;
use yii\rbac\Permission;
use open20\amos\admin\models\UserProfile;

/**
 * Class m200909_150000_update_fist_step_workflow */
class m200909_150000_update_fist_step_workflow extends Migration
{
    public function safeUp()
    {
        $this->execute("
        UPDATE preference_campain SET status = 'PreferenceCampainWorkflow/TOBEAPPROVED' WHERE id >= 1
        ");
        
    }
    
    public function safeDown()
    {
        $this->execute("
        UPDATE preference_campain SET status = 'PreferenceCampainWorkflow/INMODIFICATION' WHERE id >= 1
        ");
    }

}
