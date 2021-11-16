<?php

namespace preference\userprofile\models\base;

use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\cwh\models\base\CwhTagOwnerInterestMm;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\models\PreferenceSendingFrequency;
use Yii;

/**
 * This is the base-model class for table "preference_topic_channel_frequency".
 *
 * @property integer $id
 * @property integer $preference_channel_id
 * @property integer $preference_sending_frequency_id
 * @property string $topic_code
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property PreferenceChannel $preferenceChannel
 * @property PreferenceSendingFrequency $preferenceSendingFrequency
 */
class PreferenceTopicChannelFrequency extends Record
{
    public $isSearch = false;

/**
 * @inheritdoc
 */
    public static function tableName()
    {
        return 'preference_topic_channel_frequency';
    }

/**
 * @inheritdoc
 */
    public function rules()
    {
        return [
            [['preference_channel_id'], 'required'],
            [['preference_channel_id', 'preference_sending_frequency_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['preference_channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceChannel::className(), 'targetAttribute' => ['preference_channel_id' => 'id']],
            [['preference_sending_frequency_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceSendingFrequency::className(), 'targetAttribute' => ['preference_sending_frequency_id' => 'id']],
        ];
    }

/**
 * @inheritdoc
 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferenceuser', 'ID'),
            'preference_channel_id' => Yii::t('preferenceuser', 'FK al canale di invio notifiche'),
            'preference_sending_frequency_id' => Yii::t('preferenceuser', 'FK al alle frequenze di invio'),
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
    public function getPreferenceChannel()
    {
        return $this->hasOne(PreferenceChannel::className(), ['id' => 'preference_channel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceSendingFrequency()
    {
        return $this->hasOne(PreferenceSendingFrequency::className(), ['id' => 'preference_sending_frequency_id']);
    }

}
