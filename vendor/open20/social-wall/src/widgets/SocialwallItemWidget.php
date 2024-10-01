<?php

namespace open20\socialwall\widgets;

use yii\helpers\Json;
use yii\helpers\VarDumper;

class SocialwallItemWidget extends \yii\base\Widget
{
    public $post;
    public $postContainerId = null;
    public $showSinglePost = true;
    public $identifyPost = false;
    public $showDeleteButton = false;

    public function run()
    {
        if(!empty($this->post)) {
            return $this->render('socialwall-item', [
                'post' => $this->post,
                'postContainerId' => empty($this->postContainerId) ? $this->post['id'] : $this->postContainerId,
                'showSinglePost' => $this->showSinglePost,
                'identifyPost' => $this->identifyPost,
                'showDeleteButton' => $this->showDeleteButton,
            ]);
        }
    }
}