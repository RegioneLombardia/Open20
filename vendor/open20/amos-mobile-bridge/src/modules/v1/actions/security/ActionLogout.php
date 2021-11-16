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


use common\models\AccessTokens;
use yii\rest\Action;

class ActionLogout extends Action
{

    /**
     * @param $username
     * @return bool
     * @throws \Exception
     */
    public function run()
    {
        try {
            $authHeader = \Yii::$app->getRequest()->getHeaders()->get('Authorization');
            preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);

            /**@var $modelClass AccessTokens*/
            $modelClass = $this->modelClass;
            $AccessToken = $modelClass::findOne([
                'access_token' => $matches[1],
                'logout_at' => null
            ]);

            if ($AccessToken) {
                $AccessToken->logout();
            } else {
                throw new \Exception('Impossibile effettuare il logout');
            }

        } catch (\Exception $e) {
            throw new \Exception('Impossibile effettuare il logout');
        }


        return true;
    }

}