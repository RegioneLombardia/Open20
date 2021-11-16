<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\core\user\User;
use yii\rbac\Permission;

class m200908_110000_crea_utente_cron extends \yii\db\Migration
{
    public function safeUp()
    {
        $user = User::find()->andWhere(['username' => 'CRON'])->one();
        if (empty($user)){
            $user = new User();
            $user->username = 'CRON';
            $user->password_hash = '';
            $user->email = 'michele.zucchini+PC_CRON@open20.it';
            $userProfile = new \open20\amos\admin\models\UserProfile();
            $userProfile->nome = 'Utente';
            $userProfile->cognome = 'Cron';
            $userProfile->attivo = 1;
            $user->save(false);
            $userProfile->user_id = $user->id;
            $userProfile->save(false);
            $cronrole = Yii::$app->authManager->getRole('PC_CRON');
            Yii::$app->authManager->assign($cronrole, $user->id);
        }
    }

    public function safeDown()
    {
        // L'utente di CRON va creato e non più toccato!
        return true;
    }

}