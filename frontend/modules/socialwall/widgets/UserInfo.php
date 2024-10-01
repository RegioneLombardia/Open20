<?php


namespace frontend\modules\socialwall\widgets;

use yii\base\BaseObject;

class UserInfo extends BaseObject {
    public $created_at;
    public $description;
    public $entities;
    public $id;
    public $location;
    public $name;
    public $pinned_tweet_id;
    public $profile_image_url;
    public $protected;
    public $public_metrics;
    public $url;
    public $username;
    public $verified;
    public $withheld;
}
