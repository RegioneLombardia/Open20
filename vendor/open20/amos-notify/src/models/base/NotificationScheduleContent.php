<?php

namespace open20\amos\notificationmanager\models\base;

use open20\amos\notificationmanager\models\Notification;
use Yii;

/**
 * This is the base-model class for table "notification_schedule_content".
 *
 * @property integer $id
 * @property integer $notification_schedule_id
 * @property integer $notification_id
 * @property string $classname
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\notificationmanager\models\Notification $notification
 * @property \open20\amos\notificationmanager\models\NotificationSchedule $notificationSchedule
 */
class  NotificationScheduleContent extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_schedule_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notification_schedule_id', 'notification_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['classname','created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['notification_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notification::className(), 'targetAttribute' => ['notification_id' => 'id']],
            [['notification_schedule_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationSchedule::className(), 'targetAttribute' => ['notification_schedule_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosnotify', 'ID'),
            'notification_schedule_id' => Yii::t('amosnotify', 'Schedule'),
            'notification_id' => Yii::t('amosnotify', 'Notification'),
            'created_at' => Yii::t('amosnotify', 'Created at'),
            'updated_at' => Yii::t('amosnotify', 'Updated at'),
            'deleted_at' => Yii::t('amosnotify', 'Deleted at'),
            'created_by' => Yii::t('amosnotify', 'Created by'),
            'updated_by' => Yii::t('amosnotify', 'Updated at'),
            'deleted_by' => Yii::t('amosnotify', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(\open20\amos\notificationmanager\models\Notification::className(), ['id' => 'notification_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationSchedule()
    {
        return $this->hasOne(\open20\amos\notificationmanager\models\NotificationSchedule::className(), ['id' => 'notification_schedule_id']);
    }
}
