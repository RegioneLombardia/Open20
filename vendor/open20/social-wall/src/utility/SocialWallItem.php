<?php

namespace open20\socialwall\utility;

use Yii;
use yii\base\BaseObject;

class SocialWallItem extends BaseObject {

    const INSTAGRAM_TYPE = 1;
    const TWITTER_TYPE = 2;

    private $item_type;

    public function getItem_type() {
        return $this->item_type;
    }

    public function __construct($type, $config = []) {
        parent::__construct($config);
        $this->item_type = $type;
    }

    // Instagram e Tweeter fields
    public $id;
    //Instagram fields
    public $caption;
    public $media_url;
    public $media_type;
    public $permalink;
    public $thumbnail_url;
    public $timestamp;
    public $username;
    //Tweeter fields
    public $author_id;
    public $text;
    public $created_at;
    public $entities;
    public $attachments;
    public $in_reply_to_user_id;
    public $public_metrics;
    public $lang;
    public $userinfo;
    public $edit_history_tweet_ids;
    public $posting_date_formatted;

    public function description() {
        if ($this->item_type == static::INSTAGRAM_TYPE) {
            return $this->caption;
        } else {
            return $this->text;
        }
    }

    public function publication_date() {
        if ($this->item_type == static::INSTAGRAM_TYPE) {
            return Yii::$app->formatter->asDatetime($this->timestamp);
        } else {
            return Yii::$app->formatter->asDatetime($this->created_at);
        }
    }

    public function crTime() {
        if ($this->item_type == static::INSTAGRAM_TYPE) {
            return strtotime($this->timestamp);
        } else {
            return strtotime($this->created_at);
        }
    }

}
