<?php
namespace preference\userprofile\models;

use preference\userprofile\utility\TopicTagUtility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Model without table
 * 
 */
class StepPreferences extends Model
{
    public $topics;

    public function rules()
    {
        return [
            [['topics'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            // 'topics' => Yii::t('preferenceuser', 'List of cross topic ID'),
            'topics' => '',
        ];
    }

    public function getCrossTopicsChoiches()
    {
        return ArrayHelper::map(TopicTagUtility::getCrossTopicArray(), 'id','label');
    }

    public function getCrossTopics()
    {
        return TopicTagUtility::getCrossTopicArray();
    }

}