<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\user\User;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m190530_095945_add_appguest_user
 */
class m190530_095945_add_appguest_user extends Migration
{
    
    const APPUSERNAME = 'appguest';
    const APP_TOKEN_DEFAULT = 'FkkGThrNLDmujP78TfxgXECkrq5PH6sy';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $user = new User();

            $user->username = self::APPUSERNAME;
            $user->email = 'test@example.it';
            $user->save(false);
            $userprofile = new UserProfile();
            $userprofile->user_id = $user->id;
            $userprofile->nome = 'app';
            $userprofile->cognome = 'guest';
            $userprofile->status = \Yii::$app->workflowSource->getWorkflow(UserProfile::USERPROFILE_WORKFLOW)->getInitialStatusId();
     
            $userprofile->save(false);
            $token = new AccessTokens();
            $token->access_token = self::APP_TOKEN_DEFAULT;
            $token->user_id = $user->id;
            $token->save();
            
            UserProfileUtility::setBasicUserRoleToUser($user->id);
            
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
            $user = User::findOne(['username' => self::APPUSERNAME]);
            if(!is_null($user))
            {
                $user_profile = UserProfile::findOne(['user_id' => $user->id]);
                if(!is_null($user_profile)){
                    $user_profile->delete();
                }
                $token = AccessTokens::findOne(['user_id' => $user->id]);
                if(!is_null($token)){
                    $token->delete();
                }
                $user->delete();
            }
            
        }catch(Exception $ex){
            Console::error($ex->getTraceAsString());
        }

        return true;
    }
}
