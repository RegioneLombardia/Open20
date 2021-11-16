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
use open20\amos\admin\components\RedirectBeforeActionComponent;
use open20\amos\admin\utility\UserProfileUtility;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\rest\Controller;

/**
 * Class RedirectBeforeAction
 * @package open20\amos\admin\bootstrap
 */
class RedirectBeforeAction implements BootstrapInterface
{
    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(\yii\base\Controller::className(), \yii\base\Controller::EVENT_BEFORE_ACTION, [$this, 'startUpRedirect']);
    }
    
    /**
     * @param $event
     */
    public function startUpRedirect($event)
    {
        if (!(Yii::$app->controller instanceof Controller)) {
            $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());
            if (!is_null($adminModule)) {
                //redirect for dl semplificazione
                if (UserProfileUtility::mandatoryReconciliationPage()) {
                    $userProfileWizard = new RedirectBeforeActionComponent();
                    if (Yii::$app->controller->action->id != 'reconciliation'
                        && Yii::$app->controller->action->id != 'connect-spid'
                        && Yii::$app->controller->id != 'security'
                        && Yii::$app->controller->module->id != 'socialauth'
                        && (\Yii::$app->controller->action->id != 'logout')
                        && (\Yii::$app->controller->action->id != 'privacy')
                        && (\Yii::$app->controller->action->id != 'error')
                    ) {
                        $userProfileWizard->redirectToUrl("/" . AmosAdmin::getModuleName() . "/security/reconciliation");
                        Yii::$app->response->send();
                    }
                }
            }
        }
    }
}
