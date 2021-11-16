<?php

namespace backend\bootstrap;

use backend\modules\enti\models\EntiMantenimento;
use backend\modules\formatempadmin\models\FormatempUserProfile;
use Yii;
use yii\base\Application;
use open20\amos\core\utilities\CurrentUser;
use backend\modules\enti\models\Enti;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\web\Cookie;

/**
 */
class CheckUser extends \yii\base\Component
{

    private $platformRules = [
        'ADMIN',
        'PC_ADMIN',
        'PC_APPROVER',
        'PC_EDITOR',
        'PC_STRUCTURE_ADMIN',
        'PC_STRUCTURE_APPROVER',
        'PC_STRUCTURE_EDITOR',
    ];

    public function init()
    {
        parent::init();
        \Yii::$app->on(Application::EVENT_BEFORE_ACTION, [$this, 'onApplicationAction']);
    }

    public function onApplicationAction($event)
    {
        // a prescindere elimino il cookie che non mi fa scegliere l'identità di SPID
        $this->removeShibsessionCookie();

        if (CurrentUser::isPlatformGuest()) {
            return;
        }

        foreach ($this->platformRules as $role){
            if (Yii::$app->user->can($role)) {
                return;
            }
        }

        Yii::$app->user->logout(true);
        Yii::$app->getSession()->addFlash('danger', 'L\'utente non ha i permessi necessari all\'accesso a quest\'area');
        return;
    }

    /**
     * Eliminazione della sessione di Shiboleth
     */
    private function removeShibsessionCookie()
    {
        /** @var Cookie $cookie */
        $cookieToRemove = null;
        foreach (Yii::$app->getRequest()->getCookies() as $cookie){
            if (StringHelper::startsWith($cookie->name, '_shibsession')) {
                $cookieToRemove = $cookie;
            }
        }

        if (!is_null($cookieToRemove)) {
            $cookies = Yii::$app->response->cookies;
            unset($cookies[$cookieToRemove->name]);
        }

        // pulisco la sessione... non si sa mai...
        Yii::$app->session->remove('IDM');
    }

}
