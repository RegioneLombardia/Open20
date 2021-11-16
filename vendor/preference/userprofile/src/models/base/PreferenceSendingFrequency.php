<?php

namespace preference\userprofile\models\base;

use open20\amos\core\record\Record;
use Yii;

/**
 * This is the base-model class for table "preference_sending_frequency".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \preference\userprofile\models\PreferenceTopicChannelMm[] $preferenceTopicChannelMms
 */
class PreferenceSendingFrequency extends Record
{
    public $isSearch = false;

/**
 * @inheritdoc
 */
    public static function tableName()
    {
        return 'preference_sending_frequency';
    }

/**
 * @inheritdoc
 */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title'], 'string', 'max' => 256],
        ];
    }

/**
 * @inheritdoc
 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferenceuser', 'ID'),
            'title' => Yii::t('preferenceuser', 'Title'),
            'description' => Yii::t('preferenceuser', 'Description'),
            'created_at' => Yii::t('preferenceuser', 'Created at'),
            'updated_at' => Yii::t('preferenceuser', 'Updated at'),
            'deleted_at' => Yii::t('preferenceuser', 'Deleted at'),
            'created_by' => Yii::t('preferenceuser', 'Created by'),
            'updated_by' => Yii::t('preferenceuser', 'Updated by'),
            'deleted_by' => Yii::t('preferenceuser', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceTopicChannelMms()
    {
        return $this->hasMany(PreferenceTopicChannelMm::className(), ['preference_sending_frequency_id' => 'id']);
    }
}
