<?php

namespace backend\modules\landings\bootstrap;


use backend\modules\landings\models\PreferenceLanding;
use backend\modules\landings\models\PreferenceLandingNews;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\rest\Controller;
use open20\amos\news\models\News;

class AfterInsertNews extends Event implements BootstrapInterface
{

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(News::className(), News::EVENT_AFTER_INSERT, [$this, 'connectNewsToEvent']);
    }

    public function connectNewsToEvent($event)
    {
        if (!(\Yii::$app->controller instanceof Controller)) {
           $news = $event->sender;
           if(!empty($_GET['urlRedirect'])){
               $url = $_GET['urlRedirect'];
               if(strpos($_GET['urlRedirect'], "/landings/preference-landing/update") >= 0){
                   $urlParts = parse_url( $url);
                   parse_str($urlParts['query'], $query);
                   if(!empty($query['id'])){
                       $newsMm = new PreferenceLandingNews();
                       $newsMm->preference_landing_id = $query['id'];
                       $newsMm->news_id = $news->id;
                       $newsMm->save(false);
                   }
               }
           }
        }
    }
}