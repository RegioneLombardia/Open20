<?php

namespace preference\userprofile\models;

use preference\userprofile\utility\UserInterestTagUtility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Model without table
 * 
 */
class UserChannel extends Model
{
    public $user_id;
    public $channels;
    public $tag_id;

    public function rules()
    {
        return [
            [['user_id', 'tag_id'], 'integer'],
            [['channels'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

}
