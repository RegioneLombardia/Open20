<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\actions\security;

use open20\amos\mobile\bridge\modules\v1\models\AccessTokens;
use open20\amos\mobile\bridge\modules\v1\models\User;
use yii\base\Exception;
use yii\helpers\Json;
use yii\rest\Action;

class ActionLogin extends Action
{

    /**
     * @param $username
     * @param $password
     * @throws Exception
     */
    public function run()
    {
        /**@var $modelClass User */
        $modelClass = $this->modelClass;

        $bodyParams = \Yii::$app->getRequest()->getBodyParams();
        /**$LoginForm**/
        $LoginForm = new $modelClass();

        if ($bodyParams && $bodyParams["username"] && $bodyParams["password"]) {
            //Switch field
            if(property_exists($LoginForm, 'usernameOrEmail')) {
                $LoginForm->usernameOrEmail = $bodyParams["username"];
            } else {
                $LoginForm->username = $bodyParams["username"];
            }

            $LoginForm->username = $bodyParams["username"];
            $LoginForm->password = $bodyParams["password"];
            $LoginForm->ruolo = 'ADMIN';
            $tokenDevice = $bodyParams["token"];
            $osDevice = $bodyParams["os"];

            if ($LoginForm->validate()) {

                $User = User::findByUsernameOrEmail($LoginForm->username);

                if ($User && $User->validatePassword($LoginForm->password)) {

                    $token = $User->refreshAccessToken($tokenDevice, $osDevice);

                    $User->save();
                    $result = $User->toArray(
                        [
                            'id',
                            'username',
                            'email',
                            'slimProfile',
                            'userImage',
                        ]
                    );

                    $result['access_token'] = $token->access_token;
                    $result['fcm_token'] = $token->fcm_token;

                    return $result;
                } else {
                    throw new Exception('Username or Password Missing');
                }
            } else {
                //throw new Exception('Unable to Load Data');
                //return ($bodyParams);
                return $LoginForm->getErrors();
            }
        } else {
            return $bodyParams['username'];

            throw new Exception('Username or Password Missing');
        }

        throw new Exception('Username o password errati');
    }
}