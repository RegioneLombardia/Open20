<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m210731_110224_insert_moodle extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->insert('community',  [
            'status' => 'CommunityWorkflow/VALIDATED',
            'name' => 'Moodle General',
            'description' => 'This is only a placeholder for Moodle General default category',
            'hits' => 0,
            'logo_id' => null,
            'cover_image_id' => null,
            'community_type_id' => 3,
            'hide_participants' => 0,
            'contents_visibility' => 1,
            'for_all_user' => 0,
            'validated_once' => 1,
            'visible_on_edit' => null,
            'context' => 'open20\amos\moodle\models\MoodleCourse',
            'parent_id' => null,
            'force_workflow' => 0,
            'created_at' => '21-07-31_11:02:24',
            'updated_at' => '21-07-31_11:02:24',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $this->insert('moodle_user',  [
            'id' => 1,
            'moodle_userid' => 2,
            'moodle_email' => 'giuseppe.altomonte@open20.it',
            'moodle_name' => 'Amministratore',
            'moodle_surname' => 'Utente',
            'moodle_token' => '3e9d92599056d7eaedbd29b3c73766ae',
            'user_id' => '1',
            'created_at' => '21-07-31_11:02:24',
            'updated_at' => '21-07-31_11:02:24',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        
        $this->insert('oauth2_client',  [
            'client_id' => 'open20_fad_client_id',
            'client_secret' => 'DemoFADTest12345!',
            'redirect_uri' => 'https://moodle-341-11.devel.demotestwip.it/admin/oauth2callback.php',
            'grant_type' => 'authorization_code',
            'scope' => 'profile email',
            'created_at' => '21-07-31_11:02:24',
            'updated_at' => '21-07-31_11:02:24',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->delete('community',  [
            'status' => 'CommunityWorkflow/VALIDATED',
            'name' => 'Moodle General',
            'description' => 'This is only a placeholder for Moodle General default category',
            'hits' => 0,
            'logo_id' => null,
            'cover_image_id' => null,
            'community_type_id' => 3,
            'hide_participants' => 0,
            'contents_visibility' => 1,
            'for_all_user' => 0,
            'validated_once' => 1,            
            'visible_on_edit' => null,
            'context' => 'open20\amos\moodle\models\MoodleCourse',
            'parent_id' => null,
            'force_workflow' => 0,
            'created_at' => '21-07-31_11:02:24',
            'updated_at' => '21-07-31_11:02:24',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $this->delete('moodle_user',  [
            'id' => 1,
            'moodle_userid' => 2,
            'moodle_email' => 'giuseppe.altomonte@open20.it',
            'moodle_name' => 'Amministratore',
            'moodle_surname' => 'Utente',
            'moodle_token' => '3e9d92599056d7eaedbd29b3c73766ae',
            'user_id' => '1',
            'created_at' => '21-07-31_11:02:24',
            'updated_at' => '21-07-31_11:02:24',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $this->delete('oauth2_client',  [
            'client_id' => 'open20_fad_client_id',
            'client_secret' => 'DemoFADTest12345!',
            'redirect_uri' => 'https://moodle-341-7.devel.demotestwip.it/admin/oauth2callback.php',
            'grant_type' => 'authorization_code',
            'scope' => 'profile email',
            'created_at' => '21-07-31_11:02:24',
            'updated_at' => '21-07-31_11:02:24',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
