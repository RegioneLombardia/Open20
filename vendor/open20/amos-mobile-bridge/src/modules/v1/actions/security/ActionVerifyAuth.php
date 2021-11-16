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
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rest\Action;

class ActionVerifyAuth extends Action
{

    /**
     * @param $username
     * @param $password
     */
    public function run()
    {
        /**@var $modelClass User */
        $modelClass = $this->modelClass;

        $bodyParams = \Yii::$app->getRequest()->getBodyParams();

        if($bodyParams['token']) {
            /**
             * @var $token AccessTokens
             */
            $token = AccessTokens::findOne(['access_token' => $bodyParams['token']]);

            if($token && $token->access_token) {
                if(\Yii::$app->request->post('fcm_token')) {
                    $token->fcm_token = \Yii::$app->request->post('fcm_token');
                    $token->save(false);
                }

                return ArrayHelper::merge([
                    'status' => true
                ], $token->user->toArray(
                    [
                        'id',
                        'username',
                        'email',
                        'accessToken',
                        'fcmToken',
                        'slimProfile',
                        'userImage',
                        'socialIdm',
                    ]
                ));
            }
        }



        return [
            'error' => true,
            'error-message' => 'Token Non valido'
        ];
    }
}