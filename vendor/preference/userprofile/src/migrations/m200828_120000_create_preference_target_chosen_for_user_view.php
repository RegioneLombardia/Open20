<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\db\Migration;
use yii\rbac\Permission;
use open20\amos\admin\models\UserProfile;

/**
 * Class m200828_120000_create_preference_target_chosen_for_user_view */
class m200828_120000_create_preference_target_chosen_for_user_view extends Migration
{
    private $viewName = 'preference_target_chosen_for_user_view';

    public function safeUp()
    {
        $this->execute("

        CREATE OR REPLACE VIEW $this->viewName AS
        SELECT up.id user_profile_id, up.user_id, interessi.tag_id, interessi.root_id, tag.codice as target_code
		FROM user_profile up
		INNER JOIN cwh_tag_owner_interest_mm interessi ON 
			interessi.classname = '".addslashes(UserProfile::className())."'
            AND interessi.record_id = up.id 
        INNER JOIN tag ON tag.id = interessi.tag_id

        WHERE        
		up.deleted_at IS NULL
		AND (interessi.deleted_at IS NULL) 
		AND interessi.tag_id = interessi.root_id;
	  
        ");
        
    }
    
    public function safeDown()
    {
        $this->execute("DROP VIEW IF EXISTS $this->viewName;");
    }

}
