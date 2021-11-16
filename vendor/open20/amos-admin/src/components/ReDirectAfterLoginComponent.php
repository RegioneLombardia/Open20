<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\components
 * @category   CategoryName
 */

namespace open20\amos\admin\components;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
//use yii\base\BootstrapInterface;
use yii\base\Component;
//use yii\base\Event;

/**
 * Class FirstAccessWizardComponent
 * @package open20\amos\admin\components
 */
class ReDirectAfterLoginComponent extends Component /*implements BootstrapInterface*/
{

    /**
     * @param $url
     * @return \yii\web\Response
     */
    public function redirectToUrl($url){
        return \Yii::$app->controller->redirect([$url]);

    }
}
