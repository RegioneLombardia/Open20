<?php
/**
 */

namespace conquer\oauth2\console;

use yii\console\Controller;
use conquer\oauth2\models\AuthorizationCode;
use conquer\oauth2\models\RefreshToken;
use conquer\oauth2\models\AccessToken;

/**
 */
class Oauth2Controller extends Controller
{
    public function actionIndex()
    {
    }

    public function actionClear()
    {
        AuthorizationCode::deleteAll(['<', 'expires', time()]);
        RefreshToken::deleteAll(['<', 'expires', time()]);
        AccessToken::deleteAll(['<', 'expires', time()]);
    }
}
