<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200519_093713_insert_moodle extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('moodle_user',  [
            'id' => 1,
            'moodle_userid' => 2,
            'moodle_email' => 'giuseppe.altomonte@open20.it',
            'moodle_name' => 'Amministratore',
            'moodle_surname' => 'Utente',
            'moodle_token' => '7af9183dbc09080eb6bd9bccf09704df',
            'user_id' => '1',
            'created_at' => '2020-02-21 18:51:30',
            'updated_at' => '2020-02-21 18:51:30',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $this->insert('oauth2_client',  [
            'client_id' => 'eventiregionelombardia',
            'client_secret' => 'open2Test123456789!',
            'redirect_uri' => 'https://moodle-341-5.devel.demotestwip.it/admin/oauth2callback.php',
            'grant_type' => 'authorization_code',
            'scope' => 'profile email',
            'created_at' => '2020-02-21 18:51:30',
            'updated_at' => '2020-02-21 18:51:30',
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
        $this->delete('moodle_user',  [
            'id' => 1,
            'moodle_userid' => 2,
            'moodle_email' => 'giuseppe.altomonte@open20.it',
            'moodle_name' => 'Amministratore',
            'moodle_surname' => 'Utente',
            'moodle_token' => '7af9183dbc09080eb6bd9bccf09704df',
            'user_id' => '1',
            'created_at' => '2020-02-21 18:51:30',
            'updated_at' => '2020-02-21 18:51:30',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        $this->delete('oauth2_client',  [
            'client_id' => 'eventiregionelombardia',
            'client_secret' => 'open2Test123456789!',
            'redirect_uri' => 'https://moodle-341-5.devel.demotestwip.it/admin/oauth2callback.php',
            'grant_type' => 'authorization_code',
            'scope' => 'profile email',
            'created_at' => '2020-02-21 18:51:30',
            'updated_at' => '2020-02-21 18:51:30',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
