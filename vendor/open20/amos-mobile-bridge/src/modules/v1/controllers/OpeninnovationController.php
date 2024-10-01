<?php

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\StringHelper;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\documenti\models\Documenti;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\utility\EventsUtility;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\NewsParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\PoiNewsParser;
use open20\amos\news\models\News;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\tag\models\Tag;
use Exception;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\Expression;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\rest\Controller;

class OpeninnovationController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours,
            [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'news-list-for-preference' => ['get']
                    ],
                ],
            ]);
    }


    public function actionNewsListForPreference($offset = null, $limit = null)
    {
        $list = [];
        try {
                $namespace = News::className();
                $bodyParams = [
                    'namespace' => $namespace,
                    'offset' => $offset,
                    'limit' => $limit
                ];
                $list = PoiNewsParser::getItemsForPreference($namespace, $bodyParams);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

}