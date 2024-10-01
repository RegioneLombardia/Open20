<?php

namespace frontend\bootstrap;

use yii\base\Event;
use yii\web\View;
use yii\rest\Controller;
use Yii;

class PurifyBootstrap implements \yii\base\BootstrapInterface {

    /**
     * @param $app
     */
    public function bootstrap($app) {        
        Event::on(\yii\base\Controller::className(), \yii\base\Controller::EVENT_BEFORE_ACTION, [$this, 'purifyGet']);
    }

    /**
     * @param $event
     */
    public function purifyGet($event) {
        if (!(Yii::$app->controller instanceof Controller) && !(Yii::$app->controller instanceof yii\console\Controller)) {
            if (!empty(Yii::$app->request->get())) {
                $getParams = [];
                foreach (Yii::$app->request->get() as $k => $v) {

                    $getParams[$k] = \yii\helpers\Html::encode(\open20\amos\core\helpers\StringHelper::purifyString(strip_tags($v)));
                }
                Yii::$app->request->setQueryParams($getParams);
            }
        }
    }
}
