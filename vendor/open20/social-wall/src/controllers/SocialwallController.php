<?php

namespace open20\socialwall\controllers;

use open20\socialwall\models\search\SocialwallPostsSearch;
use open20\socialwall\models\Socialwall;
use open20\socialwall\models\SocialwallInstagramStore;
use open20\socialwall\models\SocialwallLiveContents;
use open20\socialwall\models\SocialwallPosts;
use open20\socialwall\models\SocialwallPostsInSocialwall;
use open20\socialwall\models\SocialwallPostsStaticSearch;
use open20\socialwall\utility\Social;
use open20\socialwall\utility\social_clients\instagram\InstagramClient;
use open20\socialwall\widgets\SocialwallLivePreviewWidget;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Response;

class SocialwallController extends \open20\amos\core\controllers\AmosController
{
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
                            'preview-socialwall',
                            'cache-instagram-posts',
                            'get-cached-instagram',
                            'search-asset-social',
                        ],
                        'roles' => ['SOCIALWALL_CONTENT_CREATOR']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'load-more-contents',
                        ],
                        'roles' => ['@','?']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                    'load-more-contents' => ['get'],
                ]
            ]
        ]);
    }

    public function actionCacheInstagramPosts() {
        Social::cacheAllTokensInstagramPosts();
    }

    public function actionGetCachedInstagram() {
        $instagramClient = new InstagramClient();
        VarDumper::dump($instagramClient->getFeedForSocialwallTokensId(2, ["ferrara"], 3, true), 5, true);die;
    }

    public function actionLoadMoreContents($type, $contentsId, $offset, $length) {
        if($type == Social::LIVE_WALL) {
            $postsLoaded = json_decode(SocialwallLiveContents::findOne(['id' => $contentsId])->json_data_content, true);
            $posts = array_slice($postsLoaded, $offset, $length+1, true);

            $hasMoreResults = false;

            if(count($posts) === $length+1) {
                $hasMoreResults = true;
            }

            $posts = array_slice($postsLoaded, $offset, $length, true);

            return Social::jsonEncodeData($posts + ["has_more_results" => $hasMoreResults]);
        }
        if($type == Social::PICKED_POSTS_WALL) {
            $pickedPosts = SocialwallPostsInSocialwall::find()
                ->innerJoin('socialwall_posts', '(socialwall_posts.id = socialwall_posts_in_socialwall.socialwall_posts_id AND socialwall_posts.deleted_at IS null)')
                ->andWhere(['socialwall_id' => $contentsId])
                ->orderBy('socialwall_posts.publication_date DESC')
                ->limit($length+1)
                ->offset($offset)
                ->asArray()->all();

            $formattedPostsList = [];
            foreach($pickedPosts as $pickedPost) {
                $post = SocialwallPosts::findOne(['id' => $pickedPost['socialwall_posts_id']]);
                $formattedPostsList[] = \open20\socialwall\utility\Social::formatPost(
                    $post->type,
                    $post->socialwallPostsProfile->profile_name,
                    !empty($post->profile_picture)
                        ? $post->profile_picture
                        : null,
                    $post->text,
                    !empty($post->hasOneFile('post_image')->one())
                        ? $post->getPostImage()->getWebUrl()
                        : null,
                    $post->permalink,
                    $post->publication_date,
                    $post->socialwallPostsProfile->profile_url,
                    $post->media_type
                );
            }

            $hasMoreResults = false;

            if(count($formattedPostsList) === $length+1) {
                $hasMoreResults = true;
            }

            $formattedPostsList = array_slice($formattedPostsList, 0, $length, true);

            return Social::jsonEncodeData($formattedPostsList + ["has_more_results" => $hasMoreResults]);
        }
        if($type == Social::CONTEXT_LIVE_PREVIEW) {
            $postsLoaded = json_decode(SocialwallPostsStaticSearch::findOne(['id' => $contentsId])->json_data_content, true);
            $posts = array_slice($postsLoaded, $offset, $length+1, true);

            $hasMoreResults = false;

            if(count($posts) === $length+1) {
                $hasMoreResults = true;
            }

            $posts = array_slice($postsLoaded, $offset, $length, true);

            return Social::jsonEncodeData($posts + ["has_more_results" => $hasMoreResults]);
        }
        return "";
    }

    public function actionPreviewSocialwall($socialwallTokensIds, $keywords = [], $render = true) {
        $socialwallTokensIds = explode(",", $socialwallTokensIds);
        if(!empty($keywords)) {
            $keywords = explode(",", $keywords);
        }
        $staticSearchModel = SocialwallPostsStaticSearch::findOne(['user_id' => \Yii::$app->user->id, 'context' => Social::CONTEXT_LIVE_PREVIEW . "-sess-" . \Yii::$app->session->id]);
        if(empty($staticSearchModel)) {
            $staticSearchModel = new SocialwallPostsStaticSearch();
            $staticSearchModel->user_id = \Yii::$app->user->id;
            $staticSearchModel->context = Social::CONTEXT_LIVE_PREVIEW . "-sess-" . \Yii::$app->session->id;
        }

        $encodedData = Social::jsonEncodeData(Social::getOrderedPosts($socialwallTokensIds, $keywords));
        $staticSearchModel->json_data_content = $encodedData;
        $staticSearchModel->save(false);

        $render = filter_var($render, FILTER_VALIDATE_BOOLEAN);
        if($render) {
            return SocialwallLivePreviewWidget::widget([
                'posts' => $staticSearchModel->json_data_content,
                'socialwallModelId' => $staticSearchModel->id,
            ]);
        } else {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $decodedResult = Social::jsonDecodeData($encodedData);
            foreach($decodedResult as $k => $r) {
                $decodedResult[$k]['id'] = $k;
            }
            return $decodedResult;
        }
    }

    public function actionSearchAssetSocial($social = null, $keywords = null, $createdFrom = null, $selectedPostsId = null, $render = false) {
        $results = [];

        $searchModel = new SocialwallPostsSearch();
        $searchKeys = [];
        if(!empty($socialwallTokensIds) || !empty($keywords) || !empty($createdFrom)) {
            $searchKeys['SocialwallPostsSearch'] = [];
            if(!empty($social)) {
                $searchKeys['SocialwallPostsSearch']['type'] = explode(",", $social);
            }
            if(!empty($keywords)) {
                $searchKeys['SocialwallPostsSearch']['searchPostTags'] = $keywords;
            }
            if(!empty($createdFrom)) {
                $searchKeys['SocialwallPostsSearch']['fromPublicationDate'] = $createdFrom;
            }
        }

        $search = $searchModel->search($searchKeys);
        $search->setPagination(false);
        $modelsFoundFromSearch = $search->getModels();

        /** @var SocialwallPosts $model */
        foreach ($modelsFoundFromSearch as $model) {
            $results[] = \open20\socialwall\utility\Social::formatPost(
                $model->type,
                $model->socialwallPostsProfile->profile_name,
                !empty($model->profile_picture)
                    ? $model->profile_picture
                    : null,
                $model->text,
                !empty($model->hasOneFile('post_image')->one())
                    ? $model->getPostImage()->getWebUrl()
                    : null,
                $model->permalink,
                $model->publication_date,
                $model->socialwallPostsProfile->profile_url,
                $model->media_type
            );
            $results['selected'] = false;
        }

        if(!empty($selectedPostsId)) {
            $posts = SocialwallPosts::findAll(['id' => explode(",", $selectedPostsId)]);
            foreach($posts as $model) {
                $results[] = \open20\socialwall\utility\Social::formatPost(
                    $model->type,
                    $model->socialwallPostsProfile->profile_name,
                    !empty($model->profile_picture)
                        ? $model->profile_picture
                        : null,
                    $model->text,
                    !empty($model->hasOneFile('post_image')->one())
                        ? $model->getPostImage()->getWebUrl()
                        : null,
                    $model->permalink,
                    $model->publication_date,
                    $model->socialwallPostsProfile->profile_url,
                    $model->media_type
                );
                $results['selected'] = true;
            }
        }

        $render = filter_var($render, FILTER_VALIDATE_BOOLEAN);
        if(!$render) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return $results;
        }
    }

}