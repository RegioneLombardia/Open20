<?php

namespace open20\socialwall\widgets;

use open20\socialwall\models\Socialwall;
use open20\socialwall\models\SocialwallLiveContents;
use open20\socialwall\models\SocialwallPosts;
use open20\socialwall\models\SocialwallPostsInSocialwall;
use open20\socialwall\Module;
use open20\socialwall\utility\Social;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class SocialwallFromModelWidget extends \yii\base\Widget
{
    public $model;
    public $loadedResults = 6;
    /**
     * @var String Explicit module id for fetching data from db
     */
    public $explicitModuleId = null;

    /**
     * @var int Explicit module record id for fetching data from db
     */
    public $explicitModuleRecordId = null;
    private $socialwallModelId;
    private $hasMoreResults;
    private $loadMoreContentsType;

    private function loadLiveResults($socialwallId) {
        $this->loadMoreContentsType = Social::LIVE_WALL;
        $contentsModel = SocialwallLiveContents::find()->andWhere(['socialwall_id' => $socialwallId])->orderBy('updated_at DESC')->one();
        $this->socialwallModelId = $contentsModel->id;

        $posts = json_decode($contentsModel->json_data_content, true);

        if(!empty($posts) && is_array($posts)) {
            $results = array_slice($posts, 0, $this->loadedResults+1, true);
            $this->hasMoreResults = false;

            if(count($results) === $this->loadedResults+1) {
                $this->hasMoreResults = true;
            }

            return array_merge(array_slice($posts, 0, $this->loadedResults, true), ["has_more_results" => $this->hasMoreResults]);
        } else {
            return [];
        }


    }

    private function loadPickedPosts($socialwallId) {
        $this->loadMoreContentsType = Social::PICKED_POSTS_WALL;
        $pickedPosts = SocialwallPostsInSocialwall::find()
                        ->innerJoin('socialwall_posts', '(socialwall_posts.id = socialwall_posts_in_socialwall.socialwall_posts_id AND socialwall_posts.deleted_at IS null)')
                        ->andWhere(['socialwall_id' => $socialwallId])
                        ->orderBy('socialwall_posts.publication_date DESC')
                        ->limit($this->loadedResults+1)
                        ->offset(0)
                        ->asArray()->all();

        if(!empty($pickedPosts[0])) {
            $this->socialwallModelId = $pickedPosts[0]['socialwall_id'];
        }

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

        $this->hasMoreResults = false;

        if(count($formattedPostsList) === $this->loadedResults+1) {
            $this->hasMoreResults = true;
        }

        return array_merge(array_slice($formattedPostsList, 0, $this->loadedResults, true), ["has_more_results" => $this->hasMoreResults]);
    }

    public function run()
    {
        $moduleId = null;
        $recordId = null;
        if(!empty($this->model)) {
            if(array_key_exists(get_class($this->model), \Yii::$app->getModule('socialwall')->modulesIdClassMapEnable)) {
                $moduleId = \Yii::$app->getModule('socialwall')->modulesIdClassMapEnable[get_class($this->model)];
                $recordId = $this->model->id;
            }
        }
        if(!empty($this->explicitModuleId) && !empty($this->explicitModuleRecordId)) {
            $moduleId = $this->explicitModuleId;
            $recordId = $this->explicitModuleRecordId;
        }

        if(!empty($moduleId) && !empty($recordId)) {
            $socialwall = Socialwall::findOne(['module_id' => $moduleId, 'module_record_id' => $recordId]);
            if(!empty($socialwall)) {
                if($socialwall->type === Social::LIVE_WALL) {
                    $posts = $this->loadLiveResults($socialwall->id);
                    return $this->render('socialwall-from-model', [
                        'title' => Module::t('socialwall', 'Social Wall'),
                        'posts' => $posts,
                        'socialwallModelId' => $this->socialwallModelId,
                        'showLoadMoreButton' => $this->hasMoreResults,
                        'loadMoreContentsType' => $this->loadMoreContentsType,
                    ]);
                }
                if($socialwall->type === Social::PICKED_POSTS_WALL) {
                    $posts = $this->loadPickedPosts($socialwall->id);
                    return $this->render('socialwall-from-model', [
                        'title' => Module::t('socialwall', 'Contenuti social correlati'),
                        'posts' => $posts,
                        'socialwallModelId' => $this->socialwallModelId,
                        'showLoadMoreButton' => $this->hasMoreResults,
                        'loadMoreContentsType' => $this->loadMoreContentsType,
                    ]);
                }
            }
        }
        return "";
    }
}