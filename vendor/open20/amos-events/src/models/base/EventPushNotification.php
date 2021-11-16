<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_push_notification".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $type
 * @property string $content_class
 * @property integer $content_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event $event
 * @property \open20\amos\events\models\EventPushNotificationSent[] $eventPushNotificationSents
 */
class  EventPushNotification extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_push_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'content_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['type'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['content_class', 'status'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_id' => Yii::t('amosevents', 'Event'),
            'type' => Yii::t('amosevents', 'Type'),
            'content_class' => Yii::t('amosevents', 'Class'),
            'content_id' => Yii::t('amosevents', 'Content id'),
            'status' => Yii::t('amosevents', 'Status'),
            'created_at' => Yii::t('amosevents', 'Created at'),
            'updated_at' => Yii::t('amosevents', 'Updated at'),
            'deleted_at' => Yii::t('amosevents', 'Deleted at'),
            'created_by' => Yii::t('amosevents', 'Created by'),
            'updated_by' => Yii::t('amosevents', 'Updated at'),
            'deleted_by' => Yii::t('amosevents', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\open20\amos\events\models\Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventPushNotificationSents()
    {
        return $this->hasMany(\open20\amos\events\models\EventPushNotificationSent::className(), ['event_push_notification_id' => 'id']);
    }
}
