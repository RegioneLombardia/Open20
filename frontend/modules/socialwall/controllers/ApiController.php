<?php

namespace frontend\modules\socialwall\controllers;

use frontend\modules\socialwall\models\SocialwallContentStore;
use frontend\modules\socialwall\models\SocialwallInstagramStore;
use frontend\modules\socialwall\models\SocialwallSearchHistory;
use frontend\modules\socialwall\utility\social_clients\instagram\InstagramClient;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\controllers\BaseController;
use open20\amos\core\controllers\CrudController;
use frontend\modules\socialwall\utility\social_clients\twitter\TwitterClient;
use frontend\modules\socialwall\utility\SocialWallItem;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class ApiController extends BackendController
{
    /**
     * @var string $layout
     */

    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [ 
                            'wall-posts-per-social',
                        ],
                        'roles' => ['@','?']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index',                          
                            'selected-wall-posts-per-social',
                            'search-history',
                            'instagram',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    public function actionWallPostsPerSocial(String $tag) {
        if(empty($tag)) {
            throw new \Exception("You must choose a tag");
        }

        // Every word (separated by a space) is used as a tag
        $tag = explode(" ", $tag);

        // SOCIAL CLIENTS
        $instagram = new InstagramClient();
        $twitter = new TwitterClient();

        // Fetch new instagram contents in cache (table socialwall_instagram_store) if not updated
        $fetchTimeLimit = strtotime('-1 hour');
        $lastInstagramCacheUpdate = strtotime(SocialwallInstagramStore::find()->orderBy('updated_at')->limit(1)->asArray()->one()['updated_at']);
        if($fetchTimeLimit > $lastInstagramCacheUpdate) {
            $instagram->searchMedia();
        }

        $mediasBySocial = [];

        // GET CONTENTS

        // INSTAGRAM
//        $instagram->searchMedia();
        $list = $instagram->searchMediaJson($tag);
        foreach ($list as $item) {
            // FORMAT POST CREATION DATE (HUMAN READABLE)
            $item = $this->formatPostCreationDate($item);

            $mediasBySocial['instagram'][] = new SocialWallItem(SocialWallItem::INSTAGRAM_TYPE, $item);
        }

        // TWITTER
        $list = $twitter->searchMedia(null, null, $tag);
//        VarDumper::dump($list, 20, true);die;
        foreach ($list as $item) {
            // FORMAT POST CREATION DATE (HUMAN READABLE)
            $item = $this->formatPostCreationDate($item);

            $mediasBySocial['twitter'][] =  new SocialWallItem(SocialWallItem::TWITTER_TYPE, $item);
        }

//        VarDumper::dump($mediasBySocial, 20, true);die;

        return strip_tags(Json::encode($mediasBySocial));
    }

    public function actionSelectedWallPostsPerSocial(String $moduleId, String $moduleRecordId) {
        if (empty($moduleId) && empty($recordId)) {
            throw new \Exception("You must choose a moduleId and a recordId");
        }

        $contents = SocialwallContentStore::find()
                        ->andWhere(['module_id' => $moduleId])
                        ->andWhere(['module_record_id' => $moduleRecordId])
                        ->all();

        $data = [];
        foreach($contents as $content) {
            $data[$content->social][] = $content->json_data_content;
        }

        return strip_tags(Json::encode($data));
    }

    public function actionSearchHistory(String $moduleId, String $moduleRecordId) {
        if (empty($moduleId) && empty($recordId)) {
            throw new \Exception("You must choose a moduleId and a moduleRecordId");
        }

        $contents = SocialwallSearchHistory::find()
            ->andWhere(['module_id' => $moduleId])
            ->andWhere(['module_record_id' => $moduleRecordId])
            ->asArray()
            ->one()['search_history'];

        return $contents;
    }

    public function actionUpdateInstagram() {
        $instagram = new InstagramClient();
        $instagram->searchMedia();
    }

    private function formatPostCreationDate($item) {
        $niceDate = "";

        // Case TWITTER
        if (array_key_exists('created_at', $item)) {
            $niceDate = \Yii::$app->formatter->asDatetime($item['created_at']);
        }
        // Case INSTAGRAM
        if (array_key_exists('timestamp', $item)) {
            $niceDate = \Yii::$app->formatter->asDatetime($item['timestamp']);
        }

        $item['posting_date_formatted'] = $niceDate;

        return $item;
    }

}