<?php

namespace open20\socialwall\widgets;

use open20\socialwall\utility\Social;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class SocialwallWidget extends \yii\base\Widget
{
    public $posts;
    public $socialwallModelId;
    public $showLoadMoreButton = true;
    public $loadMoreContentsType = Social::LIVE_WALL;

    public $postsContainerId = "socialwall-posts-container";

    public $showMoreBtnId = "socialwall-show-more-btn";
    public $postsToLoad = 3;
    public $showPostCheckboxes = false;
    public $postCheckboxesLabel = null;

    public function run()
    {
        if(!empty($this->posts) && is_array($this->posts)) {
            return $this->render('socialwall', [
                'posts' => $this->posts,
                'socialwallModelId' => $this->socialwallModelId,
                'showLoadMoreButton' => $this->showLoadMoreButton,
                'postsContainerId' => $this->postsContainerId,
                'showMoreBtnId' => $this->showMoreBtnId,
                'postsToLoad' => $this->postsToLoad,
                'showPostCheckboxes' => $this->showPostCheckboxes,
                'loadMoreContentsType' => $this->loadMoreContentsType,
                'postCheckboxesLabel' => $this->postCheckboxesLabel,
            ]);
        }
    }
}