<?php

namespace backend\modules\landings\bootstrap;


use backend\modules\landings\models\PreferenceLanding;
use backend\modules\landings\models\PreferenceLandingDocumenti;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveQuery;
use yii\rest\Controller;
use open20\amos\documenti\models\Documenti;

class AfterInsertDocumenti extends Event implements BootstrapInterface
{

    /**
     * @param $app
     */
    public function bootstrap($app)
    {
        Event::on(Documenti::className(), Documenti::EVENT_AFTER_INSERT, [$this, 'connectDocumentiToLanding']);
    }

    public function connectDocumentiToLanding($event)
    {
        if (!(\Yii::$app->controller instanceof Controller)) {
           $documenti = $event->sender;
           if(!empty($_GET['urlRedirect'])){
               $url = $_GET['urlRedirect'];
               if(strpos($_GET['urlRedirect'], "/landings/preference-landing/update") >= 0){
                   $urlParts = parse_url( $url);
                   parse_str($urlParts['query'], $query);
                   if(!empty($query['id'])){
                       $documentiMm = new PreferenceLandingDocumenti();
                       $documentiMm->preference_landing_id = $query['id'];
                       $documentiMm->documenti_id = $documenti->id;

                       // SORT order logica (max + 1)
                       /** @var ActiveQuery $querySort */
                       $querySort = PreferenceLandingDocumenti::find()->andWhere(['preference_landing_id' => $query['id']]);
                       $val = $querySort->max('sort_value');
                       $documentiMm->sort_value = (empty($val))? 1: ($val + 1);

                       $documentiMm->save(false);
                   }
               }
           }
        }
    }
}