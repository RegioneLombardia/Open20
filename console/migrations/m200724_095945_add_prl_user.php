<?php

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\user\User;
use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m200724_095945_add_prl_user
 */
class m200724_095945_add_prl_user extends Migration
{
    const APPUSERNAME     = 'prl';
    const APP_PWD_DEFAULT = 'FkkGThrNLDmujP78TfxgXECkrq5PH6sy';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $user = new User();

            $user->username             = self::APPUSERNAME;
            $user->email                = 'prl@prl.it';
            $user->password_reset_token = null;
            $user->password_hash        = \Yii::$app->security->generatePasswordHash(self::APP_PWD_DEFAULT);
            $user->status               = 10;
            $user->save(false);
            $userprofile                = new UserProfile();
            $userprofile->user_id       = $user->id;
            $userprofile->nome          = 'prl';
            $userprofile->cognome       = 'prl';
            $userprofile->status        = \Yii::$app->workflowSource->getWorkflow(UserProfile::USERPROFILE_WORKFLOW)->getInitialStatusId();
            $userprofile->save(false);

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
        try {
            $user = User::findOne(['username' => self::APPUSERNAME]);
            if (!is_null($user)) {
                $user_profile = UserProfile::findOne(['user_id' => $user->id]);
                if (!is_null($user_profile)) {
                    $user_profile->delete();
                }
                $user->delete();
            }
        } catch (Exception $ex) {
            Console::error($ex->getTraceAsString());
        }

        return true;
    }
}