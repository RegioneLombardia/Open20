<?php

namespace open20\socialwall\widgets;

use open20\socialwall\models\Socialwall;
use open20\socialwall\models\SocialwallLiveContents;
use open20\socialwall\utility\Social;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class SocialwallLivePreviewWidget extends \yii\base\Widget
{
    public $posts;
    public $loadedResults = 6;
    public $socialwallModelId;
    private $hasMoreResults;

    private function loadLiveResults($posts) {
        $posts = Social::jsonDecodeData($posts);

        $results = array_slice($posts, 0, $this->loadedResults+1, true);
        $this->hasMoreResults = false;

        if(count($results) === $this->loadedResults+1) {
            $this->hasMoreResults = true;
        }

        return array_merge(array_slice($posts, 0, $this->loadedResults, true), ["has_more_results" => $this->hasMoreResults]);
    }

    public function run()
    {
        if(!empty($this->posts)) {
            $posts = $this->loadLiveResults($this->posts);
            return SocialwallWidget::widget([
                'posts' => $posts,
                'socialwallModelId' => $this->socialwallModelId,
                'showLoadMoreButton' => $this->hasMoreResults,
                'loadMoreContentsType' => \open20\socialwall\utility\Social::CONTEXT_LIVE_PREVIEW,
            ]);
        }
        return "";
    }
}