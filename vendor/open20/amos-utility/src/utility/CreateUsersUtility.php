<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\utility
 * @category   CategoryName
 */

namespace open20\amos\utility\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\core\user\User;
use yii\db\Query;
use yii\base\BaseObject;
use yii\db\ActiveRecord;
use open20\amos\core\utilities\RbacUtility;
use Exception;
use yii\log\Logger;

/**
 * Class CreateUsersUtility
 * @package open20\amos\utility\utility
 */
class CreateUsersUtility extends BaseObject {

    const USER_ALL = 1;
    const USER_BASE = 2;
    const USER_FACILITATOR = 3;
    const USER_VALIDATOR = 4;
    const USER_SUPER_USER = 5;
    const DEFAULT_PASSWORD = 'Demo1234!';

    /**
     * This method returns an array indexed by user type id and the values type of the user as string.
     * @return array
     */
    public static function getUsersToCreate() {
        return [
            self::USER_ALL => 'Tutti',
            self::USER_BASE => 'Utente Base',
            self::USER_FACILITATOR => 'Utente Facilitatore',
            self::USER_VALIDATOR => 'Utente Validatore',
            self::USER_SUPER_USER => 'Utente Super User',
        ];
    }

    /**
     * This method create an user by the passed params
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $roleName
     * @param string $password
     * @return boolean
     */
    public static function generateUserByParams($name, $surname, $email, $roleName, $password) {
        try {
            $transaction = ActiveRecord::getDb()->beginTransaction();
            $commit = true;
            $user = User::findByUsernameOrEmail($email);
            if (empty($user)) {
                $newUser = UserProfileUtility::createNewAccount($name, $surname, $email);
                if (!$newUser['user'] || isset($newUser['error'])) {
                    $commit = false;
                }
                $user = $newUser['user'];
            }
            if ($commit === true) {
                $user->setPassword($password);
                $user->username = $email;
                $user->save();
                if ($user->validate() && $user->save()) {
                    $userProfile = $user->userProfile;
                    $userProfile->validato_almeno_una_volta = 1;
                    $userProfile->status = UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED;
                    $commit = $userProfile->save(false);
                    if ($roleName != '' && $commit === true) {
                       $commit = RbacUtility::assignRoleToUser($user->id, $roleName, $dontResetCache = false);
                    } 
                } else {
                    $commit = false;
                }
            }
            if ($commit === true) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $commit;
    }

}
