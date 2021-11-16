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

use DateTime;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\components\FirstAccessWizardComponent;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\rest\Controller;
use yii\web\User;

class FirstAccessWizard implements BootstrapInterface
{

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(User::className(), User::EVENT_AFTER_LOGIN, [$this, 'startUpWizard']);
    }

    public function startUpWizard($event)
    {
        $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
        if (!(Yii::$app->controller instanceof Controller)) {
            if (!is_null($adminModule)) {
                $actionId = Yii::$app->controller->action->id;

                $isRedirectToCommunity = false;
                $previousUrl           = Yii::$app->getUser()->getReturnUrl();
                $found                 = strpos($previousUrl, 'community/join?id=');
                if ($found) {
                    $isRedirectToCommunity = true;
                }

                // is set the redirect url you skip the  profile wizard,  and go to the url, at the secondo login you kskip the wizard and go in dashboard
                $userProfile = UserProfile::find()->andWhere(['user_id' => Yii::$app->user->id])->one();
                if (!empty($userProfile) && $actionId != 'send-event-mail' && !$isRedirectToCommunity) {

                    $data_iscrizione = new DateTime($userProfile->created_at);
                    $data_limite     = new DateTime('2018-07-05');
                    if (empty($userProfile->first_access_redirect_url)) {
                        $userProfileWizard = new FirstAccessWizardComponent();
                        if ($adminModule->disableFirstAccesWizard) {
                            $show = $userProfileWizard->showMessageCompleteProfile();
                        } else {
                            $show = $userProfileWizard->showWizard();
                        }
                        if (!is_null($show)) {
                            Yii::$app->response->send();
                        }
                    } elseif ($data_iscrizione > $data_limite && ($userProfile->first_access_redirect_url == '/community/join?id=2751'
                        || $userProfile->first_access_redirect_url == '/community/join?id=2750')) {
                        $userProfile->validato_almeno_una_volta = 1;
                        $userProfile->save(false);
                        $userProfileWizard                      = new FirstAccessWizardComponent();
                        $userProfileWizard->redirectToUrl($userProfile->first_access_redirect_url);
                        Yii::$app->response->send();
                    } elseif ($userProfile->first_access_login_effectuated == 0 && empty($userProfile->first_access_mail_url)) {
                        $userProfile->first_access_login_effectuated = 1;
                        $userProfile->validato_almeno_una_volta      = 1;
                        $userProfile->save(false);
                        $userProfileWizard                           = new FirstAccessWizardComponent();
                        $userProfileWizard->redirectToUrl($userProfile->first_access_redirect_url);
                        Yii::$app->response->send();
                    }
                }
            }
        }
    }


}