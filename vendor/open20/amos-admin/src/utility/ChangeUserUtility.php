<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\utility
 * @category   CategoryName
 */

namespace open20\amos\admin\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\user\User;
use yii\web\Application;

/**
 * Class ChangeUserUtility
 * @package open20\amos\admin\utility
 */
class ChangeUserUtility
{
    /**
     * Checks if a user is already present in the platform
     * @param string $email User email
     * @param bool $disableFlashMessage If true the method don't add a flash message in case of user present.
     * @param bool $returnArray If true return an array with key "present" boolean and key "message" with the same message as flash message.
     * @return bool|array
     */
    public static function checkUserAlreadyPresent($email, $disableFlashMessage = false, $returnArray = false)
    {
        $present = false;
        $message = '';
        $user = User::findByEmail($email);
        if (!is_null($user)) {
            $message = AmosAdmin::t('amosadmin', '#user_already_present', ['email' => $email]);
            if (!$disableFlashMessage && !$returnArray && (\Yii::$app instanceof Application)) {
                \Yii::$app->getSession()->addFlash('danger', $message);
            }
            $present = true;
        }
        if (!$present) {
            $user = User::findByEmailInactive($email);
            if (!is_null($user)) {
                $message = AmosAdmin::t('amosadmin', '#user_already_present_inactive', ['email' => $email]);
                if (!$disableFlashMessage && !$returnArray && (\Yii::$app instanceof Application)) {
                    \Yii::$app->getSession()->addFlash('danger', $message);
                }
                $present = true;
            }
        }
        if ($returnArray) {
            return ['present' => $present, 'message' => $message];
        } else {
            return $present;
        }
    }
}
