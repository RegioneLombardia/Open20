<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\user\User;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m201106_000000_fix_appguest_user
 */
class m201106_000000_fix_appguest_user extends Migration
{
    
    const APPUSERNAME = 'appguest';
    const APP_TOKEN_DEFAULT = 'FkkGThrNLDmujP78TfxgXECkrq5PH6sy';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {

            $this->execute("
                INSERT INTO user_profile (nome, cognome, user_id, status) VALUES ('app','guest',2,'UserProfileWorkflow/DRAFT');
            ");


            $this->execute("
                INSERT INTO access_tokens (access_token, user_id) VALUES ('FkkGThrNLDmujP78TfxgXECkrq5PH6sy',2);
            ");

            $this->execute('
                UPDATE `user` SET `password_hash` = \'$2y$13$ab216g/Nd6qwBowDuYDnNOTsYvrEwzcXsiSxc1zQGYaG5H2wnTNY.\' WHERE `username` = \''.self::APPUSERNAME.'\';
            ');
            
        } catch (Exception $ex) {
            Console::error($ex->getMessage());
            Console::error($ex->getTraceAsString());
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try{
            $this->execute("
                DELETE FROM user_profile WHERE user_id = 2;
            ");


            $this->execute("
                DELETE FROM access_tokens WHERE user_id = 2;
            ");

            $this->execute('
                UPDATE `user` SET `password_hash` = NULL WHERE `username` = \''.self::APPUSERNAME.'\';
            ');  
        }catch(Exception $ex){
            Console::error($ex->getTraceAsString());
        }

        return true;
    }
}
