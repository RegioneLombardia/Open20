<?php

namespace frontend\bootstrap;

use backend\modules\enti\models\EntiMantenimento;
use backend\modules\formatempadmin\models\FormatempUserProfile;
use http\Url;
use Yii;
use yii\base\Application;
use open20\amos\core\utilities\CurrentUser;
use backend\modules\enti\models\Enti;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\web\Cookie;

/**
 */
class CheckUser extends \yii\base\Component
{

    private $platformRules = [
        'PC_REGISTERD_USER',
    ];

    private $backendRules = [
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

        if (isset($_GET["token_invitation"])
            && !empty($_GET["token_invitation"])
            && is_null(Yii::$app->session->get('token_invitation', null))
        ) {
            Yii::$app->session->set('token_invitation', HtmlPurifier::process($_GET["token_invitation"]));
        }
    }

    public function onApplicationAction($event)
    {
        if (Yii::$app instanceof \yii\console\Application) {
            return;
        }

        if ((method_exists(Yii::$app->request, 'getIsAdmin')) && Yii::$app->request->isAdmin) {
            return;
        }

        // a prescindere elimino il cookie che non mi fa scegliere l'identità di SPID
        // $this->removeShibsessionCookie();

        if (CurrentUser::isPlatformGuest()) {
            return;
        }

        foreach ($this->platformRules as $role){
            if (Yii::$app->user->can($role)) {
                return;
            }
        }

        // se mi trovo qui, l'utente è loggato e non ha un profilo adatto ad entrare
        // controllo che sia un utente di backend, se sì allora personalizzo il messaggio
        $isBackendUser = false;
        foreach ($this->backendRules as $role){
            if (Yii::$app->user->can($role)) {
                $isBackendUser = true;
            }
        }

        Yii::$app->user->logout(true);
        if ($isBackendUser) {
            Yii::$app->getSession()->addFlash('danger', 'Utente già presente a sistema con ruolo operatore / validatore');
        } else {
            Yii::$app->getSession()->addFlash('danger', 'Accesso non autorizzato. Non si dispone del profilo corretto.');
        }
        return;
    }

    /**
     * Eliminazione della sessione di Shiboleth
     */
    private function removeShibsessionCookie()
    {
        /** @var yii\web\Cookie $cookie */
        $cookieToRemove = null;
        foreach (Yii::$app->getRequest()->cookies as $cookie){
            \yii\helpers\VarDumper::dump($cookie->name,3,true);
            if (\yii\helpers\StringHelper::startsWith($cookie->name, '_shibsession')) {
                $cookieToRemove = $cookie;
            }
        }

        if (!is_null($cookieToRemove)) {
            $cookies = Yii::$app->response->cookies;
            unset($cookies[$cookieToRemove->name]);
        }

        // pulisco la sessione... non si sa mai...
        // Yii::$app->session->remove('IDM');
    }
}
