<?php

namespace frontend\bootstrap;

use yii\base\Event;
use yii\web\View;

class AddGoogleTagManager implements \yii\base\BootstrapInterface
{

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        if (\Yii::$app instanceof \yii\web\Application && (YII_ENV == 'prod')) {
            Event::on(View::className(), View::EVENT_BEGIN_BODY , [$this, 'beginBody']);
        }
    }

    public function beginBody($event)
    {
        if (isset(\Yii::$app->params['google_tag_manager_code']) && !empty(\Yii::$app->params['google_tag_manager_code'])) {
            /** @var View $view */
            $view = $event->sender;

            $view->registerJs("
                (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','".\Yii::$app->params['google_tag_manager_code']."');"
                , View::POS_HEAD
                ,'google_tag_manager_code');

            echo $view->render('@frontend/bootstrap/google-tag-manager-body', [
                'tagManagerCode' => \Yii::$app->params['google_tag_manager_code']
            ]);
        }
    }

}