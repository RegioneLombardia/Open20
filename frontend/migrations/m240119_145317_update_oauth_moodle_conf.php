<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

use yii\db\Migration;

/**
 * Class m230911_145317_populate_table_file_categorie
 */
class m240119_145317_update_oauth_moodle_conf extends Migration
{
    const NOME_TABELLA = 'oauth2_client';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update(self::NOME_TABELLA, 
                ['grant_type' => 'authorization_code','scope' => 'profile email'], 
                ['client_id' => 'open20_fad_client_id','client_secret'=> 'DemoFADTest12345!','redirect_uri' => 'https://moodle-open2demo.devel.demotestwip.it/admin/oauth2callback.php']);
    }
}