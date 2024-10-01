<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\socialwall\controllers 
 */
 
namespace open20\socialwall\controllers;

use open20\socialwall\models\Socialwall;
use open20\socialwall\models\SocialwallPosts;
use open20\socialwall\models\SocialwallPostsProfiles;
use open20\socialwall\models\SocialwallPostsStaticSearch;
use open20\socialwall\models\SocialwallTokens;
use open20\socialwall\utility\Social;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;

/**
 * Class SocialwallPostsController 
 * This is the class for controller "SocialwallPostsController".
 * @package open20\socialwall\controllers 
 */
class SocialwallPostsController extends \open20\socialwall\controllers\base\SocialwallPostsController
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
                            'load-post-from-social',
                            'search-post-to-asset',
                            'socialwall-select-posts',
                            'socialwall-selected-post-to-append',
                        ],
                        'roles' => ['SOCIALWALL_CONTENT_CREATOR']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'load-post-from-social' => ['get', 'post'],
                    'search-post-to-asset' => ['get', 'post'],
                ]
            ]
        ]);
    }

    public function actionLoadPostFromSocial() {
        $this->setUpLayout('list');

        $modelSocialwall = new Socialwall();

        $post = \Yii::$app->request->post();
        if(!empty($post) && $modelSocialwall->load($post)) {
            $isToday = floor((strtotime($post['Socialwall']['until']) - strtotime(date("Y-m-d")))/(60*60*24)) == 0;
            $socialChoices = !empty($post['Socialwall']['socialChoices']) ? $post['Socialwall']['socialChoices'] : array_keys(Social::getSocialwallTokensEnabled());
            $since = !empty($post['Socialwall']['since'])
                        ? ($post['Socialwall']['since'] . " 00:00:00")
                        : null;
            $until = !empty($post['Socialwall']['until'])
                        ? ($post['Socialwall']['until'] . " " . ($isToday ? date("H:i:s", strtotime("-1 minute")) : "23:59:59"))
                        : null;
            $tags = !empty($post['Socialwall']['socialTags'])
                        ? explode(",", $post['Socialwall']['socialTags'])
                        : [];
            $socialPosts = Social::getOrderedPosts($socialChoices, $tags, 30, $since, $until);

            SocialwallPostsStaticSearch::deleteAll(['context' => Social::CONTEXT_POSTS_SEARCH . "-sess-" . \Yii::$app->session->id, 'user_id' => \Yii::$app->user->id]);
            $staticSearch = new SocialwallPostsStaticSearch();
            $staticSearch->user_id = \Yii::$app->user->id;
            $staticSearch->context = Social::CONTEXT_POSTS_SEARCH . "-sess-" . \Yii::$app->session->id;
            $staticSearch->json_data_content = Social::jsonEncodeData($socialPosts);
            $staticSearch->search_tags = $post['Socialwall']['socialTags'];
            $staticSearch->save(false);

            $socialPosts = Social::jsonDecodeData($staticSearch->json_data_content);

            $modelSocialwall->socialChoices = $post['Socialwall']['socialChoices'];
            $modelSocialwall->socialTags = $post['Socialwall']['socialTags'];
            $modelSocialwall->since = $post['Socialwall']['since'];
            $modelSocialwall->until = $post['Socialwall']['until'];
        }

        if(empty($post['Socialwall']['socialChoices'])) {
            $modelSocialwall->socialChoices = array_keys(Social::getSocialwallTokensEnabled());
        }

        return $this->render('load-post-from-social', [
            'modelSocialwall' => $modelSocialwall,
            'posts' => !empty($socialPosts) ? $socialPosts : null,
        ]);
    }

    public function actionSearchPostToAsset($postId) {
        $staticSearchRecord = SocialwallPostsStaticSearch::findOne(['user_id' => \Yii::$app->user->id, 'context' => Social::CONTEXT_POSTS_SEARCH . "-sess-" . \Yii::$app->session->id]);
        if(!empty($staticSearchRecord)) {
            $posts = Social::jsonDecodeData($staticSearchRecord->json_data_content);
            if(array_key_exists($postId, $posts)) {
                $module = \Yii::$app->getModule('attachments');

                $postData = $posts[$postId];

                // Search social profile to link
                $socialwallPostProfile = SocialwallPostsProfiles::find()->andWhere(['profile_url' => $postData['profile_url']])->orderBy('updated_at DESC')->limit(1)->one();
                if(empty($socialwallPostProfile)) {
                    $socialwallPostProfile = new SocialwallPostsProfiles();
                    $socialwallPostProfile->type = $postData['type'];
                    $socialwallPostProfile->profile_name = $postData['name'];
                    $socialwallPostProfile->profile_url = $postData['profile_url'];
                    $socialwallPostProfile->save(false);

                    // ATTACH PROFILE PICTURE
                    if(!empty($postData['profile_picture_url'])) {
                        // download image from url and save tempFile
                        $explode = explode('/', explode("?", basename($postData['profile_picture_url']))[0]);
                        $filename = explode("?", basename($postData['profile_picture_url']))[0];
                        if (!empty($explode)) {
                            $filename = end($explode);
                        }
                        $img = $module->getTempPath() . '/' . $filename;

                        //save file
                        file_put_contents($img, file_get_contents($postData['profile_picture_url']));
                        $module->attachFile($img, $socialwallPostProfile, 'profile_picture', true, false);
                    }
                }

                $socialwallPost = new SocialwallPosts();
                $socialwallPost->type = $postData['type'];
                //$socialwallPost->profile_name = $postData['name'];
                if($socialwallPost->type !== Social::TWITTER) {
                    $socialwallPost->text = Social::encodedStringForDb($postData['post_text']);
                } else {
                    $socialwallPost->text = $postData['post_text'];
                }
                $socialwallPost->permalink = $postData['permalink'];
                $socialwallPost->publication_date = date('Y-m-d H:i:s', $postData['post_datetime']);
                //$socialwallPost->profile_url = $postData['profile_url'];
                $socialwallPost->media_type = $postData['media_type'];
                $socialwallPost->search_tags = $staticSearchRecord->search_tags;
                $socialwallPost->socialwall_posts_profiles_id = $socialwallPostProfile->id;
                $socialwallPost->save(false);

                // POST IMAGE
                if(!empty($postData['post_image_url'])) {
                    // download image from url and save tempFile
                    $explode = explode('/', explode("?", basename($postData['post_image_url']))[0]);
                    $filename = explode("?", basename($postData['post_image_url']))[0];
                    if (!empty($explode)) {
                        $filename = end($explode);
                    }
                    $img = $module->getTempPath() . '/' . $filename;

                    //save file
                    file_put_contents($img, file_get_contents($postData['post_image_url']));
                    $module->attachFile($img, $socialwallPost, 'post_image', true, false);
                }

                return "/socialwall/socialwall-posts/update?id=" . $socialwallPost->id;
            } else {
                throw new ForbiddenHttpException("content not found");
            }
        } else {
            throw new ForbiddenHttpException("socialwall_posts_search record not found");
        }
    }

    public function actionSocialwallSelectPosts() {
        $this->setDataProvider($this->modelSearch->search(\Yii::$app->request->getQueryParams()));

        return $this->renderPartial(
            'socialwall-select-posts',
            [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null,
                'moduleName' => ($this->moduleName) ? $this->moduleName : null,
                'contextModelId' => ($this->contextModelId) ? $this->contextModelId : null,
            ]
        );
    }

    public function actionSocialwallSelectedPostToAppend($postId) {
        $post = SocialwallPosts::findOne(['id' => $postId]);

        $postTypeIcon = null;
        switch ($post->type) {
            case \open20\socialwall\utility\Social::FACEBOOK:
                $postTypeIcon = '<span class="icon icon-lg icon-white mdi mdi-facebook" style="font-size: 40px;"></span>';
                break;
            case \open20\socialwall\utility\Social::INSTAGRAM:
                $postTypeIcon = '<span class="icon icon-lg icon-white mdi mdi-instagram" style="font-size: 40px;"></span>';
                break;
            case \open20\socialwall\utility\Social::TWITTER:
                $postTypeIcon = '<span class="icon icon-lg icon-white mdi mdi-twitter" style="font-size: 40px;"></span>';
                break;
        };

        $imageElem = "";
        if(!empty($post->hasOneFile('post_image')->one())) {
            $imageElem = "<img src=\"{$post->getPostImage()->getWebUrl('square_small')}\" width='100px' />";
        }

        return Social::jsonEncodeData([
            'id' => $postId,
            'publication_date' => \Yii::$app->formatter->asDate(
                strtotime($post->publication_date),
                isset(
                    \Yii::$app->components['formatter']['datetimeFormat']) ?
                    \Yii::$app->components['formatter']['datetimeFormat'] :
                    'd-m-Y H:i:s'),
            'typeIcon' => $postTypeIcon,
            'text' => \yii\helpers\StringHelper::truncate(str_replace("&amp;", "&", strip_tags($post->text)), 200, '...'),
            'image' => $imageElem,
            'preview' => \open20\socialwall\widgets\SocialwallItemWidget::widget([
                "post" => \open20\socialwall\utility\Social::formatPost(
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
                ),
                "showSinglePost" => true,
                "postContainerId" => "post-preview-".$post->id,
                "identifyPost" => true,
                "showDeleteButton" => false,
            ])
        ]);
    }

}
