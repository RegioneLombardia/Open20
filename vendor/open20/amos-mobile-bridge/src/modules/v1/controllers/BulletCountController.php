<?php
namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\chat\models\Message;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\discussioni\models\search\DiscussioniTopicSearch;
use open20\amos\news\models\News;
use open20\amos\news\models\search\NewsSearch;
use Yii;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\rest\Controller;
use yii\swiftmailer\Logger;

class BulletCountController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours, [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'bullet-count' => ['post'],
                    ],
                ],
        ]);
    }

    /**
     * 
     * @return integer
     */
    public function actionBulletCount()
    {
        $count = 0;
        try {
            //Request params
            $bodyParams = Yii::$app->getRequest()->getBodyParams();

            //Refference namespace
            $classname = $bodyParams['namespace'];

            $count = $this->makeBulletCounter(
                Yii::$app->getUser()->getId(),
                $classname,
                $this->evaluateSearchModel($classname)
            );
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $count <= 99 ? $count : "99";
    }

    /**
     * 
     * @param integer $userId
     * @param string $className
     * @param Query $externalQuery
     * @return int
     */
    private function makeBulletCounter($userId = null, $className = null, $externalQuery = null)
    {
        if (($userId == null) || ($className == null)) {
            return 0;
        }

        if($className == Message::className()) {
            return $externalQuery->count();
        }

        $count = 0;
        $notifier = Yii::$app->getModule('notify');

        if ($notifier) {
            $count = $notifier->countNotRead(
                $userId,
                $className,
                $externalQuery
            );
        }

        return $count;
    }

    /**
     * 
     * @param string $classname
     */
    private function evaluateSearchModel($classname)
    {
        $query = null;

        switch ($classname) {
            case News::className():
                $modelSearch = new NewsSearch();
                $query = $modelSearch->buildQuery([], 'own-interest');
                break;
            case DiscussioniTopic::className():
                $modelSearch = new DiscussioniTopicSearch();
                $query = $modelSearch->buildQuery([], 'own-interest');
                break;
            case Message::className():
                $query = Message::find()
                    ->andWhere([
                    'is_new' => true,
                    'receiver_id' => Yii::$app->getUser()->getId(),
                    'is_deleted_by_receiver' => false
                ]);
                break;
        }

        return $query;
    }
}
