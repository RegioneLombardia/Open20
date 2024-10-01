<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 05/09/2018
 * Time: 10:27
 */

namespace amos\sitemanagement\bootstrap;


use amos\sitemanagement\models\SiteManagementLandingPubblication;
use open20\amos\admin\components\FirstAccessWizardComponent;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\AmosController;
use yii\base\BootstrapInterface;
use yii\base\Controller;
use yii\base\Event;
use yii\base\ViewEvent;
use yii\base\View;
use yii\base\WidgetEvent;
use yii\helpers\Url;
use yii\web\User;
use yii\widgets\Breadcrumbs;



class ShowLanding implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        Event::on(AmosController::className(), AmosController::EVENT_BEFORE_ACTION, [$this, 'redirectToLanding']);
//        Url::current();
    }

    public function redirectToLanding($event)
    {
        $actual_link = "/". \Yii::$app->controller->action->getUniqueId();
        $pubbl = SiteManagementLandingPubblication::find()->innerJoinWith('landing')->andWhere(['site_management_landing_pubblication.url' => $actual_link])->one();
        if($pubbl){
//            \Yii::$app->session->set('link', false);
            $cookies = \Yii::$app->request->cookies;
            $cookies->readOnly = false;
            $cookie = $cookies->get('sm-adv-'.$pubbl->id);
            pr($cookie);
            if(empty($cookie)) {
                pr("presente");
//                \Yii::$app->session->set('link', true);

                $cookies->add( new \yii\web\Cookie([
                    'name' => 'sm-adv-'.$pubbl->id,
                    'value' => 'zh-CN',
//                    'expire' => time()+60
//                    'expire' => time()+60*60*24*180
                ]));
//                pr($pubbl->landing->view_path);
//                if(strpos($pubbl->landing->view_path, '@')) {

                    return \Yii::$app->controller->redirect(['/sitemanagement/landing-page/index', 'id' => $pubbl->landing->id]);
//                }
//                else {
//                    return \Yii::$app->controller->redirect($pubbl->landing->view_path);
//                }
            }
        }
    }

}