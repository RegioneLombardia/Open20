<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\commands
 * @category   CategoryName
 */

namespace open20\amos\admin\commands;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\user\User;
use yii\console\Controller;
use Yii;

class UserUtilityController  extends Controller
{


    /**
     *
     */
    public function actionBasicUserAssign(){
        /** @var AmosAdmin $admin */
        $admin = AmosAdmin::instance();
        $userClass = $admin->model('User');
        $users = $userClass::find()->all();
        /** @var User $user */
        foreach ($users as $user){
            $roles = Yii::$app->authManager->getAssignments((int) $user->id);
            if(empty($roles)) {
                Yii::$app->getAuthManager()->assign(Yii::$app->getAuthManager()->getRole('BASIC_USER'), $user->id);
                $this->log ('Add BASIC_USER to : id'. $user->id . "  username: " . $user->username);
            }
        }
    }

    /**
     * @param $message
     */
    private function log($message){
        echo ($message ."\n");
    }
}