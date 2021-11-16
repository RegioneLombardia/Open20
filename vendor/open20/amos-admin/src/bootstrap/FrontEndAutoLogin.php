<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\bootstrap
 * @category   CategoryName
 */

namespace open20\amos\admin\bootstrap;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\TokenGroup;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\httpclient\Client;
use yii\rest\Controller;
use yii\web\User;

class FrontEndAutoLogin implements BootstrapInterface
{

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
        if (!is_null($adminModule)) {
            if ($adminModule->frontend_auto_login) {
                Event::on(User::className(), User::EVENT_AFTER_LOGIN,
                    [$this, 'goAutoLogin']);
            }
        }
    }

    /**
     *
     * @param type $event
     */
    public function goAutoLogin($event)
    {
        if (!(Yii::$app->controller instanceof Controller)) {
            $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
            if (!is_null($adminModule)) {

                $this->callUrl($this->getLinkWithToken(Yii::$app->user->id,
                        $adminModule->frontend_autologin_token_group));
            }
        }
    }

    /**
     *
     * @param type $user_id
     * @param type $event_string
     * @return type
     */
    public function getLinkWithToken($user_id, $event_string)
    {
        $link       = null;
        $tokengroup = TokenGroup::getTokenGroup($event_string);

        if ($tokengroup) {

            $tokenUser = $tokengroup->generateSingleTokenUser($user_id);
            if (!empty($tokenUser)) {
                $link = $tokenUser->getFrontendTokenLink();
            }
        }
        return $link;
    }

    /**
     *
     * @param type $url
     * @return type
     */
    public function callUrl($url)
    {
        $client   = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->send();
        return $response->getContent();
    }
}