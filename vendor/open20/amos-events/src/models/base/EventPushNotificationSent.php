<?php

namespace open20\amos\events\models\base;

use Yii;

/**
* This is the base-model class for table "event_push_notification_sent".
*
    * @property integer $id
    * @property integer $event_push_notification_id
    * @property integer $user_id
    * @property string $sent_at
    * @property string $status
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \open20\amos\events\models\EventPushNotification $eventPushNotification
    */
 class  EventPushNotificationSent extends \open20\amos\core\record\Record
{
    public $isSearch = false;

/**
* @inheritdoc
*/
public static function tableName()
{
return 'event_push_notification_sent';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['event_push_notification_id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['sent_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['event_push_notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventPushNotification::className(), 'targetAttribute' => ['event_push_notification_id' => 'id']],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosevents', 'ID'),
    'event_push_notification_id' => Yii::t('amosevents', 'Push notification'),
    'user_id' => Yii::t('amosevents', 'User'),
    'sent_at' => Yii::t('amosevents', 'Sent at'),
    'status' => Yii::t('amosevents', 'Status'),
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
    public function getEventPushNotification()
    {
    return $this->hasOne(\open20\amos\events\models\EventPushNotification::className(), ['id' => 'event_push_notification_id']);
    }
}
